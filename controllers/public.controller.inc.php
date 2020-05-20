<?php



/* 

 *Controller used where the authenticated userRole = "rep" - i.e sales rep

 */



// CRUD Controller

switch( strtolower($req['e']) ) {

	

	case 'recover password':

    

        if($S->recoverPassword($req['recovery_email'])){

            $flash_msg = "your password details have been sent, please check your inbox for details";

            $S->nextview='default';

        } else {

            $error_msg = "Password recovery failed ";

        }

        

    break;

    

    case"login":

    case"logout":

			//echo "logging out any current user<br>";

			$S->logout(); // log out previous user

			// if there is a current users then log them out properly before doing login

            

            

			

			//echo "Now authenticating new user with username=".$req['username'].":".$req['password']."<br>";

			$S->authenticate($req['username'],$req['password']);

			

			

			if (!$S->loggedIn) {

                if($_POST){

                    $error_msg = "login failed";

                }

				

                $S->nextview = "login";

				//echo dumper($S);

				

			} else {

                $flash_msg = "login successful";

                // TODO Add entry to logins table for darrens emarketing support
                // record_login();
                

				// if login successful then choose the controller to chain to

				if ($S->role =="client" or $S->role =="rep" or $S->role=="manager") {
                    

					$chain_to_controller = "controllers/client.controller.inc.php"; // setting this will cause second controller to be called after this one is done	

				} elseif ($S->role == "admin" ) {

					$chain_to_controller = "controllers/admin.controller.inc.php"; 

				}
                
			

			}

            

    break;

    default:

    if($req['v'] != 'contactus'){

        //header ('location: ../cms/');

        //exit;

    }

    

				

	

}



// if PUBLIC request v= list_products then we change to public_list_products

if ($req['v'] == "list_products") {

		$S->nextview = "public_list_products";

}





	



		$template = "templates/main";

		

?>