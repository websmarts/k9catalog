<?
class EmailAdmin
{
	function EmailAdmin()
	{
	}
	/** Method **/
	function sendEmail($to, $subject, $body, $from)
	{
			// simple email using to, subject, body and from
			mail($to, $subject, $body, $from ."\r\n");
	}
}
?>