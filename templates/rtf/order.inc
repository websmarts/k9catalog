<?
   // including main script file:
   include "lib/rtf_class.php";
   // this will be the name of our RTF file:
   $file_rtf = "report.rtf";
   // HTTP headers saying that it is a file stream:
   Header("Content-type: application/octet-stream");
   // passing the name of the streaming file:
   Header("Content-Disposition: attachment; filename=$file_rtf");
   // here is the text of our RTF file:
   $html_text = "Hello <b>World!<b>";
   // creating class object and passing to it the path to configuration file:
   $rtf = new RTF("rtf_config.inc");
   // passing the text to the object:
   
   // $markup = make_report(); // 
$markup = <<<EOD


<table width=90% border=1 >
	<tr>
		<td><img anchor=incell src=images/k9logo.jpg height=18 width=60 ></td><td rowspan=2 bgcolor=30 color=#000000 align=center><font color=#FFFFFF size=14 ><b>Kennels<br>Bedding<br>Pet Toys<br>Treats<br>Bowls<br>Leads & Collars</b></font></td>
	</tr>
	<tr><td><p lindent=2 rindent=5><font size=16><b>Product Code</b></font> </td></tr>
</table>
<table width=90% border=1>
	<tr height=150>
		<td >
			<p  lindent=5 before=5 after=5><font size=16><b>Dog Bowls - Stainless Steel and Rubber Trim</b></font><br>
			<p><img  wrap=updown anchor=incell src=source/b199.jpg width=50 height=40 ><br>XXX
			
		</td>
	</tr>
	<tr height=12>
		<td bgcolor=0 color=#004000  ><p before=2 align=right rindent=5><font size=14 color=#ffffff ><b>Manufacturers of Quality Pet Products & Accessories</b></font></td>
	</tr>
</table>

<table width=90% border=0 ><tr><td> <p after=2 align=center ><font size=10>K9 Homes 119 McEwan Rd Heidelberg Heights, Victoria. Australia.<br>Phone:61 3 9455 2277 Fax:61 3 9455 9187  Email sales@k9homes.com.au</font></td></tr></table>
EOD;

   $rtf->parce_HTML($markup);
   // getting RTF code:
   $fin = $rtf->get_rtf();
   // streaming the file to the user:
   echo $fin;
?>