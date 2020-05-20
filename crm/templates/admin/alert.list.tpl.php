<!-- alert.list.tpl.php -->
<script language="JavaScript">
	function category_add(id,count){

			var cat_id="";
			for(var i=0;i<count;i++)
			{
				str = document.getElementById('categorycheck'+i).value;
				if(document.getElementById('categorycheck'+i).checked){
					if(cat_id=="")
						cat_id=str;
					else
						cat_id=cat_id+","+str;
					}
				}
			window.location = "index.php?_a=business_alert_list&listingID="+id+"&cat_id="+cat_id+"&test=set";
	}
	function back_history(id){
			window.location = "index.php?_a=listingapprove&listingID="+id;
	}
</script>

<? $currentURL= urlencode(HOST."/admin/index.php?".$_SERVER['QUERY_STRING']); ?>
   <table width="60%"><tr><td>
		
    <table width="100%"><tr>
	
<!--  BUSINESS CATEGORY LIST -->	
	 <td valign="top">
    	
    	<table>
    	    <tr>
	          <td class="header" bgcolor="#000066" align="center"><font color="#FFFFFF">Business Categories</font> </td>
        	</tr>
	   	<?php if ( count($data['sponsBusDAO']) > 0 ){
			$i=0;
		 ?>
	   		<?php foreach ( $data['sponsBusDAO'] as $cat ) { 
					foreach ( $data['list2catDAO'] as $list2cat ) { 
						if($list2cat==$cat[0])
						{
							$checked="checked";
							break;
						}
						else
							$checked="";
					 }
			
			?>
    		<tr><td><input type=checkbox name="categorycheck<?php echo $i;?>" id="categorycheck<?= $i ?>" value="<?=$cat[0];?>" <?=$checked?> ><?=$cat[1];?></td></tr>
    	  <?php $i++;}
		  } else { ?>
        <tr>
          <td class="blue-bold" align="left"> &bull;&nbsp;No Business Categories Available<br>
          </td>
        </tr>
        <? } ?>
    	</table>	
    	
    </td>
<!--  BUSINESS LIST -->	 
	<td valign="top">
	<table width="100%" cellpadding="1" cellspacing="0" style="border:1px dotted #000000;">
        <? 
		 if($data['totalRecord']>0) { ?>
        <tr>
          <td class="header" bgcolor="#000066" align="center" ><font color="#FFFFFF">Business 
            List</font> </td>
        </tr>
        <? 
				$i=0;
				while ($data['listingDAO']->fetch()) { 
				?>
        <tr <? print ($i%2==0?'bgcolor="#EEEEEE"':'bgcolor="#FFFFC0"') ?>>
          <td valign="top" height="30" >
				  <b><? echo($data['listingDAO']->businessName); ?></b><br><br>
				  	 <? echo($data['listingDAO']->address1); ?>  -  <? echo($data['listingDAO']->address2); ?><br>
					 <? echo($data['listingDAO']->city); ?>
					 </td>
        </tr>

        <? 
			$i++;
			} ?>
        <? } else { ?>
        <tr>
          <td class="blue-bold" align="left"> &bull;&nbsp;No Business Listings 
            Available<br>
          </td>
        </tr>
        <? } ?>
   </table>
	</td>
	

	
	</tr> </table>
 
 
</td></tr>
   		<tr>
			<td align="center"><input type="button" value="Test" class="inputbutton" onClick="javascript:category_add('<?php echo $_REQUEST['listingID'];?>','<?php echo count($data['sponsBusDAO']);?>');">
			<input type="button" value="Back" class="inputbutton" onClick="javascript:back_history('<?php echo $_REQUEST['listingID'];?>');"></td>
		</tr>

   </table>
