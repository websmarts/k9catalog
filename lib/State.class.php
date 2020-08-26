<?php



class State

{

    public $id; // id of k9 person

    public $role; // role of logged in person

    public $privileges; // csv list of allowable stuff to do beyond what role allows

    public $client = array(); // array with client data if client logged in

    public $rep_name = ""; // if role = rep then this holds rep 'login' name

    public $firstname; // k9 person real firstname

    public $lastname; // k9 person real laststname

    public $recordmileage = false;

    public $newLogin = false; // flag to indicate just logged in

    public $loggedIn = false;

    public $req; // an array holding http req params

    public $basket;

    public $basket_instructions;

    public $basket_prices; // added 2009 so reps can add custom prices

    public $order_contact;

    public $lastview;

    public $nextview;

    public $db;

    public $orderId; // the current system_orders table record order_id (string)



    public function __construct($db)

    {

        $this->db = $db;
    }



    /**

     * Refresh database handle if lost - ie after serialisation to/from session

     *

     * @param mixed $db

     */

    public function setDB($db)

    {

        $this->db = $db;
    }



    /**

     * Handles all login attempts

     * Login from Users - authenticated against user table

     * Login from Clients - authenticated against clients table

     *

     * @param mixed $username

     * @param mixed $password

     */

    public function authenticate($username, $password)

    {



        $this->loggedIn = false;

        $username = strtolower($username); //remove iPad annoying first letter capitalisation

        $password = strtolower($password);



        if (empty($username) || empty($password)) {

            return;
        }

        // Check if User login

        $qry = "SELECT * from `users` WHERE `name`='$username' AND md5('$password') = `pass` ";

        $result = $this->db->GetArray($qry);



        //echo dumper($result);



        if ($result && $result[0]['name'] == $username) {

            $this->id = $result[0]['id'];

            $this->role = $result[0]['role'];

            $this->rep_name = $result[0]['name'];

            $this->rep_firstname = $result[0]['firstname'];

            $this->rep_lastname = $result[0]['lastname'];

            $this->privileges = $result[0]['privileges'];

            $this->recordmileage = $result[0]['record_mileage'];

            $this->loggedIn = true;

            $this->client = array(); //clear any residual client ID



        }



        // If not yet login_success then check to see if it is a client trying to login

        if (!$this->loggedIn) {

            $qry = "SELECT client_id,name from `clients` where status='active' and `login_user`='$username' AND login_pass ='$password'";

            $result = $this->db->GetArray($qry);

            if ($result) {


                // Logit
                $qry = 'INSERT into logins (`email`,`datetime`) VALUES("' . $username . '","' . date("Y-m-d H:i:s") . '")';
                //echo dumper($qry); exit;

                $this->db->execute($qry);

                // log client in

                $this->id = 0; // used to indicte user is a client in code

                $this->client = $result[0];

                $this->privileges = '';

                //$this->client_id=$result[0]['client_id'];

                $this->role = "client";

                $this->loggedIn = true;

                $this->newLogin = true;

                restore_client_basket();
            }
        }



        if (!$this->loggedIn) {

            $this->logout();
        }
    }

    public function recoverPassword($email)

    {

        $validemailpattern = '/\\A(?:^([a-z0-9][a-z0-9_\\-\\.\\+]*)@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.(com|org|net|biz|info|name|net|pro|aero|coop|museum|[a-z]{2,4}))$)\\z/i';



        if (preg_match($validemailpattern, $email)) {

            // get the password from the client file

            $qry = 'select `login_pass` from `clients` where login_user="' . $email . '" limit 1';

            $result = $this->db->GetArray($qry);

            if ($result) {

                $message = 'Your password for k9homes website is: ' . $result[0]['login_pass'] . "\n";

                $subject = "Recover password email";

                $headers = 'From: webmaster@k9homes.com.au' . "\r\n" .

                    'Reply-To: info@k9home.com.au' . "\r\n" .

                    'X-Mailer: PHP/' . phpversion();



                return mail($email, $subject, $message, $headers);
            }
        } else {

            return 0;
        }
    }

    public function is_valid()

    {

        return $this->loggedIn;
    }



    public function is_valid_client()

    {

        if ($this->role == 'client' && $this->getClientId() > 0 && $this->id == 0) {

            return $this->getClientId();
        } else {

            return 0;
        }
    }

    public function isInternalUser()

    {

        // is it a k9 user

        if ($this->loggedIn && $this->id > 0) {

            return 1;
        } else {

            return 0;
        }
    }



    public function getClientId()

    {

        if (isset($this->client['client_id']) && $this->client['client_id'] > 0) {

            return $this->client['client_id'];
        } else {

            return 0;
        }
    }

    public function getClientData()

    {

        if (is_array($this->client) && isset($this->client['client_id']) && $this->client['client_id'] > 0) {

            return $this->client;
        } else {

            return 0;
        }
    }



    public function getK9UserId()

    {

        return $this->id;
    }



    public function logout()

    {



        save_basket();



        $this->id = false;

        $this->role = false;

        $this->privileges = '';

        $this->client = array();

        $this->rep_name = '';

        $this->firstname = '';

        $this->lastname = '';

        $this->newLogin = false;

        $this->loggedIn = false;

        /*

        $this->basket = false;

        $this->basket_instructions ='';

        $this->basket_prices = array();

         */



        $this->clearBasket(); // replaces above three line



        $this->nextview = "default";
    }



    public function show_current_state()

    {



        $r = "<p>S->id=" . $this->id .

            "<br>S->role=" . $this->role .

            "<br>client_id=" . $this->getClientId() .

            "<br>count(S->basket)=" . count($this->basket) .

            "<br>S->lastview=" . $this->lastview .

            "<br>S->nextview=" . $this->nextview .

            "<br>S->newLogin=" . $this->newLogin .

            "<br>S->module=" . $this->module .

            "</p>";



        return $r;
    }



    public function getUserRole()

    {

        return $this->role;
    }



    public function clearBasket()

    {

        unset($this->basket);

        unset($this->basket_prices);

        unset($this->basket_instructions);

        unset($this->order_contact);

        unset($this->freight_charge);
    }



    /**

     * return the number of item types in basket

     *

     */

    public function basket_count()

    {

        if (isset($this->basket) && is_array($this->basket)) {

            return count($this->basket);
        } else {

            return 0;
        }
    }



    /**

     * Check k9 users privileges

     */

    public function checkPrivileges($key = '')

    {

        if (!empty($key)) {

            return preg_match('/' . $key . '/i', $this->privileges);
        } else {

            return false;
        }
    }

    /**

     * Save state to $_SESSION

     * for next request to use

     */

    public function save()

    {

        $_SESSION['S'] = $this;
    }
}
