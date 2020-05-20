<?php



// list.php

// get browse categories



//echo dumper($req);

if (!isset($req['date'])) {

    $date = date('Y-m-d');

} else {

    $date = $req['date'];

}



// Get list of notes for the day

// Only show Maxbones and CDL to darren who has an id of 6

$hiddenClientIds = $S->getK9UserId() == 6 ? '0' : '3215,3438'; // cdl & Maxbones



$sql = 'select

            users.name as callby,

            clients.name as `client`,

            call_type_options.call_type,

            ch.*

            FROM contact_history as ch

            JOIN users ON users.id = ch.call_by

            JOIN `clients` ON clients.client_id=ch.client_id

            JOIN call_type_options ON call_type_options.id = ch.call_type_id

            WHERE DATE(ch.call_datetime) = "' . $date . '"

            AND ch.client_id NOT IN(' . $hiddenClientIds . ')';



if (isset($req['k9user']) && (int) $req['k9user'] > 0) {

    $sql .= ' and ch.call_by = ' . (int) $req['k9user'];

}

$sql .= ' ORDER BY ch.call_datetime asc ';

$clientnotes = do_query($sql);



$sql = ' select so.*, clients.name as clientname from system_orders as so

            join clients on clients.client_id=so.client_id


            where DATE(so.modified)="' . $date . '"

            AND so.client_id NOT IN(' . $hiddenClientIds . ')'; // removed condition: where so.reference_id > 0



if (isset($req['k9user']) && (int) $req['k9user'] > 0) {

    $sql .= ' and so.reference_id = ' . (int) $req['k9user'];

}



$sql .= ' order by  `so`.`modified` asc ';

//echo $sql;

$reporders = do_query($sql);



// Get a list of any instore stocktakes taken today

$sql = 'SELECT DISTINCT

                clients.client_id as client_id,

                clientstock.datetime as stockcountdate,

                clients.name AS client,

                users.id AS rep_id

                FROM clientstock

                JOIN clients ON clients.client_id=clientstock.client_id

                JOIN users ON users.id = clientstock.user_id

                WHERE DATE(clientstock.datetime)="' . $date . '"';

if (isset($req['k9user']) && (int) $req['k9user'] > 0) {

    $sql .= ' and clientstock.user_id = ' . (int) $req['k9user'];

}

$clientstockcounts = do_query($sql);



// Get startKM and times for users with mileage

$sql = "SELECT * FROM travel WHERE '" . $date . "' = DATE_FORMAT(startkm_timestamp,'%Y-%m-%d')";

$travelstarts = do_query($sql);

