<!-- login.tpl.php -->
	
	<table width="100%" class="boxed" >
		 <tr>
		
		<td valign=top bgcolor="#eeeeee">
	
			<table  width=300 cellpadding="5" >
				
			  <tr>
			    <td width="90" align="right" >Account name: </td>
			    <td class="inputbox2"><?PHP echo $renderer->elementToHtml('userName'); ?></td>
			  </tr>
			  
			  <tr>
			    <td align="right">Password:</td>
			    <td><?PHP echo $renderer->elementToHtml('password'); ?></td>
			  </tr>
			
			  <tr>
			    <td>&nbsp;</td>
			    <td><?PHP echo $renderer->elementToHtml('login_button'); ?>
			    	<?PHP echo $renderer->elementToHtml('rememberme'); ?> </td>
			  </tr>
			  
			</table>
		
		</td>
		
	</tr>
</table>
