<?php
// *-*-*-*-*-*		- GET SESSION VARIABLES -
		$dataBank =& new DataBank(SESSION_KEY_CLIENT_FORMS);
		$accountID = (int)$dataBank->getVar('s_c_account_id');
		$pg = @$_REQUEST['pg'];

		# GENERATE CONDITION
		
		$cond = "status!='closed' AND accountID=$accountID";
		
		# TOTAL RECORD COUNT FOR PAGING
		//DB_DataObject::debugLevel(1);
		$localadsobj = DB_DataObject::factory('localads');
		$localadsobj->whereAdd($cond);
		$totRec = $localadsobj->find();

		#++++ PAGING PARAMETERS
		$page = $pg;
		$gap = 10;
		$start = $pg*$gap;
		$end = $start + $gap;
		if($end>$totRec)
			$end = $totRec;
		$pagingInfo = paging1($totRec,$start,$end,$page,$gap);

		$localadsobj->orderBy('created DESC');
		$localadsobj->limit($start,$gap);
		$localadsobj->find();
		
		$view = $action;

?>