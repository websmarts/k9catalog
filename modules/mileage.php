<?php

// list.php
// get browse categories


//echo dumper($req);

    
    // Get orders in the last 30 days to show in list
    $sql =' select * from `travel` where `sales_rep_id`='.$S->id .'  and  isnull(`endkm`) order by `traveldate` desc limit 1';
    //echo $sql;
    $lasttravelrecord = do_query($sql);
