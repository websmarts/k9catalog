<?


   // including main script file:


   include "rtf_class.php";


   // this will be the name of our RTF file:


   $file_rtf = "Test.rtf";


   // HTTP headers saying that it is a file stream:


   Header("Content-type: application/octet-stream");


   // passing the name of the streaming file:


   Header("Content-Disposition: attachment; filename=$file_rtf");


   // here is the text of our RTF file:


   $html_text = "Hello <b>World!<b>";


   // creating class object and passing to it the path to configuration file:


   $rtf = new RTF("rtf_config.inc");


   // passing the text to the object:


   $rtf->parce_HTML($html_text);


   // getting RTF code:


   $fin = $rtf->get_rtf();


   // streaming the file to the user:


   echo $fin;


?>