<?php

// list.php
// get browse categories


//echo dumper($req);


$sql=  'select 
        ch.id,
        ch.call_type,
        ch.call_type_id,
        ch.call_datetime as calldate, 
        ch.note, c.contacts,
        u.name as callby, 
        ch.contacted 
        from contact_history as ch 
        join clients as c on ch.client_id=c.client_id
        left join users as u on u.id=ch.call_by
        where ch.id='.$req['id'].'
        ';
            
    $clientnote=do_query($sql);
    setFormData($clientnote[0]);
    
    