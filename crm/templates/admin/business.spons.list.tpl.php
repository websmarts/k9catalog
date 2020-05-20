<!-- business.spons.list.tpl.php -->
<div align="center">
<table width="100%">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%" cellpadding="1" cellspacing="0" style="border:1px dotted #000000;">
        <? 
		if($data['totalSponsRecord']>0) 
		{ 
		?>
        <tr>
          <td class="header" bgcolor="#000066" align="center"> Sponsored By</td>
        </tr>
			<?
			while($data['sponsBusDAO']->fetch())
			{
			?>
        <tr <? print ($i%2==0?'bgcolor="#EEEEEE"':'bgcolor="#FFFFC0"') ?>>
          <td valign="top"><b><? echo($data['sponsBusDAO']->businessName); ?></b> </td>
        </tr>
        <tr <? print ($i%2==0?'bgcolor="#EEEEEE"':'bgcolor="#FFFFC0"') ?>>
          <td valign="top"><b>&curren;</b> <? echo($data['sponsBusDAO']->city); ?> - <? echo(ucfirst($data['sponsBusDAO']->postcode)); ?><br>
          </td>
        </tr>
        <tr <? print ($i%2==0?'bgcolor="#EEEEEE"':'bgcolor="#FFFFC0"') ?>>
          <td valign="top"><b>&curren;</b> <? echo($data['sponsBusDAO']->phoneAreacode); ?>-<? echo($data['sponsBusDAO']->phoneNumber); ?></td>
        </tr>
        <tr <? print ($i%2==0?'bgcolor="#EEEEEE"':'bgcolor="#FFFFC0"') ?>>
          <td valign="top">&nbsp;</td>
        </tr>
        <? 
			}
		?>
		<?	
		} else { ?>
        <tr>
          <td class="blue-bold" align="left"> &bull;&nbsp;No Sponsored Businesses Found<br>
          </td>
        </tr>
        <? } ?>
      </table></td>
    </tr>
</table>
</div>
