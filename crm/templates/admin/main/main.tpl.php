<!-- main.tpl.php -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?= $data['title'] ?></title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>
<div style="background:#fff;border:1px solid #ccc;text-align:left;width:988px ">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" style="border:1px solid #ccc">
    <tr>
      <td colspan="2">
		  <table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td width="341" valign="top"><img src="../images/olr_logo.jpg" width="341" height="78"></td>
			  <td width="77" valign="top"><img src="../images/olr_newspaper.jpg" width="77" height="100"></td>
			  <td width="100%" style="background:url(../images/locator_background.jpg) repeat-x;"><span class="tophead">Administration</span></td>
			  </tr>
		  </table>
	  </td>
    </tr>
	<tr>
	<td class="header" bgcolor="#000066"><?= $data['header'] ?></td>
	<td class="header" bgcolor="#000066" width="20%"><div align="right"><? if($data['userName']!='') { print "Welcome " . $data['userName'] . " | <a href='index.php?_a=logout'>Logout</a>" ; }?></div></td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
	  </tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td width="15%" valign="top" height="100%">
					<? 
						if($data['left-panel']!='')
						include_once ($data['left-panel']); 
					?>
				</td>
				<td valign="top">
					<table width="100%" align="center">
						<?PHP if (isset($data['formErrors']) && count($data['formErrors']) > 0) { ?>
						<tr>
						  <td align="left" class="red-bold">
							Please make the following corrections and submit this page again.
							<ul>
							<?PHP foreach ($data['formErrors'] as $key => $errorValue) { ?>
							<li class="formError"><? echo $errorValue; ?></li>
							<?PHP } ?>
							</ul>  
						  </td>
					  	</tr>
					  	<? } ?>
						<?PHP if (isset($data['msgData']) && $data['msgData'] != '') { ?>
						<tr>
						  <td class="msg"><? print $data['msgData'] ?></td>
					  </tr>
					  <? } ?>
						<tr>
							<td>
								<? 
									if($data['bodyForm']!='')
										print $data['bodyForm']; 
								?>
								<? 
									if($data['body']!='')
										include_once($data['body']); 
								?>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
		  </table>
	 	</td>
	</tr>	
  </table>
</div>
</center>
</body>
</html>