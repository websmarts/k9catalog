<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
	<title><?= $data['title'] ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="style.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	

	
<div id="container">
	
		<? //echo dumper($data['accountType']); ?>
	
	

	
	<div id="login">
		<? if (!empty($data['accountType'])) { ?> 
			<a href="?_a=logout">Logout</a>
		<? } else { ?>
			<a href="?_a=login">Login</a>
		<? } ?>
 	</div>
 		
	<!-- Start MAIN -->
	<div id="main">
		
	
	
		<!-- do we need a left_panel -->
		<? if ($data['left_panel'] > '') {
				$content_left_margin = 210;
		?>		
			<div id="left_panel" >left panel</div>
		<? } ?>
		
		<!-- do we need a right_panel -->
		<? if ($data['right_panel'] > '') {
				$content_right_margin = 210;
		?>		
			<div id="right_panel" ><?	include_once($data['right_panel']) ?>	</div>
		<? } ?>
		
		<div id="content" style="<?=$content_left_margin > 0?'margin-left:'.$content_left_margin.'px;':''?><?=$content_right_margin > 0?'margin-right:'.$content_right_margin.'px;':'';?>">   
		
	
				<!-- Report FORM ERRORS -->
		    <?PHP if (isset($data['formErrors']) && count($data['formErrors']) > 0) { ?>
		    <div id="formerrors">
		          	  <ul>
		              <?PHP foreach ($data['formErrors'] as $key => $errorValue) { ?>
		              <p ><? echo $errorValue; ?></p>
		              <?PHP } ?>
		    </div>       
		    <? } ?>
    
        <!-- Display a FORM if there is one -->     
		    <? if($data['bodyForm']!='') { ?>
		    <div id="bodyform">
						<?		print $data['bodyForm']; ?>
				</div>
				<? } ?>              
		    
		    <!-- include the Content page set -->              
		    <? if($data['body']!=''){ ?>		     		
						<?	include_once($data['body']) ?>				 		
				 <? } ?>
			</div><!-- end content div -->
			
			
						 
						 
			<div id="footer" ><?php print sprintf("%.6f seconds",$data['execution_time']); ?>&nbsp;<?=$data['view_template']?></div>
	</div>

	<!-- Degug footer -->
	
</div>
</body>
</html>

