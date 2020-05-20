 <?php

/*
 * web2printer
 * Module     		   : web2printer.php
 * License			   : pos-x
 * Version    		   : 4 
 * Reference Plattform: 4.0.6; 4.2.1
 *
 * Copyright (c) 1998 - 2003 pda-systems.COM. All rights reserved.
 * http://www.pda-systems.com
 *
 * This software is confidential and proprietary information of
 * pda-systems.COM ("Confidential Information"). You  shall not
 * disclose such Confidential Information and shall use it only
 * in accordance  with the  terms of the  license agreement you
 * entered into with pda-systems.COM.
 *
 * PDA-SYSTEMS.COM MAKES NO  REPRESENTATIONS OR WARRANTIES ABOUT THE
 * SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR IMPLIED, INCLUDING
 * BUT  NOT  LIMITED TO  THE  IMPLIED WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGMENT. 
 * PDA-SYSTEMS.COM SHALL NOT  BE LIABLE FOR ANY DAMAGES  SUFFERED BY 
 * LICENSEE AS A  RESULT OF  USING,  MODIFYING OR  DISTRIBUTING THIS 
 * SOFTWARE OR ITS DERIVATES.
 */

$web2printerVersion = "<strong>web2printer 4</strong>";

//require("web2printer_conf.inc");

// page url header:
$pageUrlHeader = "<b>This page URL</b>:<br>";

// page url crossreference header:
$pageXRefURLHeader = "<b>Links:</b>";

// page images crossreference header:
$pageXRefImages = "<b>Images:</b>";

// active link
// 0 - hyperlinks not active (clickable) in the resulting html file
// 1 - hyperlinks active (clickable) in the resulting html file
$activeLinks = 0;

// 0 disable footer 
// 1 enable  footer 
// 2 use custom footer 
$footer = 0;
// custom footer text - if you not enjoy the standard footer
// this page was generated with ....
$customFooter = "";

// path to your e-mail template (absolute or relative to web2printer.php)
$myEMailForm    = "./form.html";

// 0 disable email logging
// 1 enable email loggin
$logmail		= 0;
// logfile path - put it outside your html path (ONLY PATH !!!)
$logfile	   = "/www/htdocs/htsuccess/stack/scriptlog";
// maximum number of mails a single (visitor)ip can send per day
// important to prevent abuse
$maxip	   = 10;



//  start time
$timingStart = "";
// url to filename to process
$urlToPrint = "";
// file content
$content = "";
// host name
$scripthost = "";
// image processing flag
$clearImages = 0;
// array with hyperlinks
$links = Array();
// number of links
$linkCount = 0;
// array with images
$images = Array();
// number of images
$imageCount = 0;
// hyperlink processing flag
$resolveLink = 0;
// send a E-Mail
$email = 0;
// send a E-Mail stage
$stage = 1;
// array with hyperlinks
// array with parsed meta tags
$metaTags = Array();
// number of parsed meta tags
$metaCount = 0;
// copyright string parsed from meta tags
$copyright = "";
// meta tag processing flag
$preserveMetaTags = 0;
// stylsheet name string
// empty or not set: stylsheet informations supressed
// otherwise	   : stylsheet used for printing
$stylesheet       = "site.css";
// page title
$title = "";
// original page
$authentyPage = "";

// check setting form web2printer_conf.inc, if not set choose defaults

if (!isset ($pageUrlHeader)) {
	$pageUrlHeader = "<b>This page URL</b>:<br>";
}

if (!isset ($pageXRefURLHeader)) {
	$pageXRefURLHeader = "<b>Links:</b>";
}
if (!isset ($pageXRefImages)) {
	$pageXRefImages = "<b>Images:</b>";
}
if (!isset ($activeLinks)) {
	$activeLinks = 0;
}
if (!isset ($footer)) {
	$footer = 1;
}
if ($footer == 2 && !isset ($customFooter)) {
	$customFooter = "please define a footer";
}
	
web2printer (); // initialze
prepare();	    // format
($stage == 1)?display():sendMail(); // display or send as e-mail
exit(); 

function web2printer () {
	
	global $HTTP_ENV_VARS;
	global $HTTP_GET_VARS;
	global $HTTP_POST_VARS;
	global $HTTP_SERVER_VARS;
	global $timingStart;
	global $urlToPrint;
	global $clearImages;
	global $scripthost;
	global $resolveLink;
		
	global $stage;
	global $to;
	global $from;
	global $name;
	global $comment;
	global $preserveMetaTags;
	global $stylesheet;
	global $email;
		
	// start from here :-)
	$timingStart = explode(' ', microtime());
	if (isset ($HTTP_GET_VARS["page"])) {
		$page = $HTTP_GET_VARS["page"];
	}
	
	if (isset ($HTTP_GET_VARS["img"])) {
		$clearImages = $HTTP_GET_VARS["img"];
	}
		
	// REMOVE FOR BASIC -------------------------------------------------------------------
	if (isset ($HTTP_GET_VARS["lnk"])) {
		$resolveLink = $HTTP_GET_VARS["lnk"];
	}
	if (isset ($HTTP_GET_VARS["tgs"])) {
		$preserveMetaTags = $HTTP_GET_VARS["tgs"];
	}
	if (isset ($HTTP_GET_VARS["style"])) {
		$stylesheet = $HTTP_GET_VARS["style"];
	}
	
	// REMOVE FOR WEBMASTER ---------------------------------------------------------------
	if (isset ($HTTP_GET_VARS["mail"])) {
		$email = $HTTP_GET_VARS["mail"];
	}
		
	if (isset ($HTTP_POST_VARS["stage"])) {
		$stage = 2;
		$clearImages = 1;
		if (isset ($HTTP_POST_VARS["mail"])) {
			$email = $HTTP_POST_VARS["mail"];
		}
		if (isset ($HTTP_POST_VARS["to"])) {
			$to = $HTTP_POST_VARS["to"];
		}
		if (isset ($HTTP_POST_VARS["from"])) {
			$from = $HTTP_POST_VARS["from"];
		}
		if (isset ($HTTP_POST_VARS["name"])) {
			$name = $HTTP_POST_VARS["name"];
		}
		if (isset ($HTTP_POST_VARS["comment"])) {
			$comment = $HTTP_POST_VARS["comment"];
		}
	}
		
	// END REMOVE FOR WEBMASTER -----------------------------------------------------------
	// END REMOVE FOR BASIC ---------------------------------------------------------------
	
	if (!isset ($HTTP_ENV_VARS["HTTP_HOST"])) {
		$scripthost = "http://".$HTTP_SERVER_VARS["HTTP_HOST"];
	}
	else {
		$scripthost = "http://".$HTTP_ENV_VARS["HTTP_HOST"];
	}
	
	// Process the filename
	// if we nether not have an referer nor an http get -> error
	if (!isset ($page)) {
		if (isset ($HTTP_SERVER_VARS["HTTP_REFERER"])) {
			$page = $HTTP_SERVER_VARS["HTTP_REFERER"];
		}
		else {
			if (isset ($HTTP_ENV_VARS["HTTP_REFERER"])) {
				$page = $HTTP_ENV_VARS["HTTP_REFERER"];
			}
			else {
				$page = "";
			}
		}
	}
	if ($page == "") {
		die ("Sorry! We need an HTTP_REFERER or javascript enabled to print this page.");
	}
	else {
		$urlToPrint = $page;
	}
}

function get_current () {
	global $timingStart;
	$stop_time = explode(' ', microtime());
	$current = $stop_time[1] - $timingStart[1];
	$current += $stop_time[0] - $timingStart[0];
	return sprintf("%.6f seconds",$current);
}

// convert an local filename to uri
function makeUrl ($input) {
	
	global $HTTP_SERVER_VARS;
	global $scripthost;
	$retval = "";
	
	$input = strtr ($input, "\\", "/");
	$tokens = parse_url ($input);
	if 	(isset ($tokens["host"])) {
		$retval =  $input;
	}
	else {
		$docRoot = strtr ($HTTP_SERVER_VARS['DOCUMENT_ROOT'], "\\", "/");
		$input = str_replace ($docRoot, "", $input);
		$retval = $scripthost.(($input{0} == '/')?"":"/").$input;
	}
	return $retval;
}

function scanWebSite($scan) {
	
	$fp=@fopen ($scan, "r");
	if (!$fp) {
		die ("could not open:".$scan);
	}
	$plainFile = "";
	while (!feof ($fp)) {
		$plainFile.=fgets($fp, 4096);
	}
	fclose($fp);
	return $plainFile;
}

// prepare the file content
function prepare () {
	global $urlToPrint;
	global $clearImages;
	global $content;
	global $resolveLink;
	global $preserveMetaTags;
	global $stylesheet;
	global $email;
	global $stage;
	global $title;
	global $authentyPage;
	global $copyright;
		
	$plainFile = scanWebSite ( $urlToPrint );
	// preserve title tag
	
	if (preg_match("/(<title>)(.*)<\/title>/i", $plainFile, $regs)) {
		$title = trim($regs[2]); 
        if ($title == "") $title = "web2printer generated file"; 
	} 
	
	// REMOVE FOR BASIC -------------------------------------------------------------------
	// REMOVE FOR WEBMASTER ---------------------------------------------------------------
	if ($email == 1) {
		if ($stage == 1) {
			$content = insertForm($plainFile);
			return;
		}
		$authentyPage = $plainFile;
	}     
	// END REMOVE FOR BASIC ----------------------------------------------------------------
	// END REMOVE FOR WEBMASTER ------------------------------------------------------------
	
	// parse copyright
	
	if (preg_match ("/<meta([ ]?)*name([ ]?)*\=([ ]?)*\"copyright\"([ ]?)*content([ ]?)*\=([ ]?)*\"([^\"])*\"/i", $plainFile, $regs)) {
		$copyright =  preg_replace ("/<meta([ ]?)*name([ ]?)*\=([ ]?)*\"copyright\"([ ]?)*content([ ]?)*\=([ ]?)*\"/i", "", $regs[0]);
		$copyright = substr ($copyright, 0, strpos($copyright, "\""));
	}
			
	getMeta($plainFile);
	
	// clean up embedded styles
	$plainFile = preg_replace ("/style[ ]*=\"[^\"]*\"/i", "", $plainFile);
	
	// cut off header
	$plainFile = preg_replace ("/<(\/)?head[ ]*>/i", "<CUTOFFHEADER>", $plainFile);
	$start = strpos ($plainFile, "<CUTOFFHEADER>");
	if ($start) {
		$end   = strpos ($plainFile, "<CUTOFFHEADER>", $start+1);
		if ($end && $end > 0) {
			$left = substr($plainFile, 0, $start);
			$right = substr($plainFile, $start+$end+15);
			$plainFile = $left.$right;
		}
		else {
			die ("unbalanced header tags");
		}
	}
	
	$offset = 0;
	$loop = true;
	while ($loop) {
		$start = strpos ($plainFile, "<!-- web2printer:start -->", $offset);
		if ($start) {
			$end = strpos ($plainFile, "<!-- web2printer:end -->", $offset);
			if ($end) {
				$content = $content.substr ($plainFile, $start, $end-$start);
			} else {
				die ("Missing <!-- web2printer:end -->");
			}
		}
		else {
			$loop = false;
		}
		$offset = $end+1;
	} // while
		
	if ($clearImages > 0) {
		clearImages();
	}
	
	// REMOVE FOR BASIC -------------------------------------------------------------------
	if (0 < $resolveLink) {
		solveLinks();
	}
	// END REMOVE FOR BASIC ---------------------------------------------------------------
}

function insertForm($file) {
	
	global $myEMailForm;
	global $form;
	$fp = fopen ($myEMailForm, "r");
	$form = fread($fp, 64000);
	return eregi_replace("<!-- web2printer:email !-->", $form, $file);
}

// image processing
// 0 : leave images
// 1 : supress image printing
// 2 : replace images with [IMAGE]
// 3 : replace images with alt

function clearImages() {
	global $content;
	global $images;
	// number of images
	global $imageCount;
	global $clearImages;
		
	$offset = 0;
	$content = preg_replace ("/<img$/i", "<img", $content);
	
	switch ($clearImages) {
		// leave images
		case 0: break;
		// remove images
		case 1: $content = preg_replace ("/<img([^>]?)*>/i", "", $content);
		break;
		// replace images with [IMAGE]
		case 2: $content = preg_replace ("/<img([^>]?)*>/i", "[Image]", $content);
		break;
		// REMOVE FOR BASIC -------------------------------------------------------------------
		// REMOVE FOR WEBMASTER ---------------------------------------------------------------
		// replace images with alt
		case 3: while ($start = strpos ($content, "<img", $offset)) {
			$offset = $start+1;
			$end = strpos ($content, ">", $offset);
			$entry =substr ($content, $start, $end - $start);
			if (preg_match ("/alt([^=]?)*=([^\"]?)*\"([^\"]?)*/i", $entry, $regs)) {
				$alt = preg_replace("/alt([^=]?)*=([^\"]?)*\"/i", "", $regs[0]);
				$left = substr($content, 0, $start);
				$right = substr($content, $end+1);
				$subst = "<b>[Image:".$alt."]</b>&nbsp;";
				$content = $left.$subst;
				$content = $content.$right;
				$offset = $end+strlen($subst);				
			}
			$value = "";
		} // while
			$content = preg_replace ("/<img([^>]?)*>/i", "[Image]", $content);
		break;
		// generate image crossreference
		case 4: while ($start = strpos ($content, "<img", $offset)) {
			$offset = $start+1;
			$end = strpos ($content, ">", $offset);
			$entry =substr ($content, $start, $end - $start);
			if (preg_match ("/src([^=]?)*=([^\"]?)*\"([^\"]?)*/i",$entry, $regs)) {
				$src = preg_replace("/src([^=]?)*=([^\"]?)*\"/i", "", $regs[0]);
				$src = makeUrl($src);
				$left = substr($content, 0, $start);
				$right = substr($content, $end+1);
				$found = false;
				$count = 0;
				// check if we have this image already
				while ($count < $imageCount) {
					if (0 == strcmp ($images[$count], $src)) {
						$found = true;
						break;
					}
					++$count;
				}
				if (!$found) {
					$images[$imageCount] = $src;
					++$imageCount;
				}
				$subst = "[IMAGE No:<b>".($count+1)."</b>]";
				$content = $left.$subst;
				$content = $content.$right;
				$offset = $end+strlen($subst);
			}
			$value = "";
		} // while
		break;
		// END REMOVE FOR WEBMASTER -----------------------------------------------------------
		// END REMOVE FOR BASIC ---------------------------------------------------------------
		default: die ("bad parameter img !");
	}
}

// REMOVE FOR BASIC -------------------------------------------------------------------

// link processing
// 1 : create crossreference
// 2 : create crossreference with removed get params
// 3 : convert hyperlinks to text

function solveLinks() {
	
	global $content;
	global $links;
	global $linkCount;
	global $activeLinks;
	global $resolveLink;
		
	$offset = 0;
	$value = "";
	$len = strlen ($content);
				
	$content = preg_replace ("/<a/i", "<a", $content);
	$content = preg_replace ("/<\/a/i", "</a", $content);
	while ($start = strpos ($content, "<a", $offset)) {
		$offset = $start+2;
		$end = strpos ($content, "</a", $offset);
		if ($end == false) {
			die ("html error: missing &lt;/a&gt; starting at:<br>".substr($content, $start, 255));
		}
		$entry =substr ($content, $start, $end - $start+4);
		if (preg_match ("/href([^=]?)*=([^\"]?)*\"([^\"]?)*/i",$entry, $regs)) {
			$link = strtolower(preg_replace("/href([^=]?)*=([^\"]?)*\"/i", "", $regs[0]));
			if ($resolveLink == 2) {
				if ($pos = strpos ($link, "?")) {
					$link = substr ($link, 0, $pos);
				}
			}
			if (0 < strlen ($link)) {
				if ($resolveLink < 3) {
					$count = 0;
					$found = false;
					// check if we have this href already
					while ($count < $linkCount) {
						if (0 == strcmp ($links[$count], $link)) {
							$found = true;
							break;
						}
						++$count;
					} // while
					if (!$found) {
						$links[$linkCount] = $link;
						++$linkCount;
					}
					++$count;
				}
				if (0 == $activeLinks) {
					$entry = preg_replace("/<a([^>]?)*>/i", "", $entry);
					$entry = preg_replace("/<\/a([^>]?)*>/i", "", $entry);
				}
			}
			$left = substr($content, 0, $start);
			$right = substr($content, $end);
			if ($resolveLink < 3) {
				$content = $left."<b>".$entry."[".$count."]</b>";
				$content = $content.$right;
			} else {
				$content = $left.$entry.$right;
			}
			
			
		} // if preg_match
		$offset = $start+strlen ($entry);
		if ($offset >= $len) {
			break;
		}
	} // while
}
// END REMOVE FOR BASIC -------------------------------------------------------------------

function getMeta($plainFile) {
	
	$offset = 0;
	$end	= 0;
	global $metaTags;
	global $metaCount;
	global $copyright;
	
	$plainFile = preg_replace ("/<(:space:)*meta/i", "<meta", $plainFile);
	while ($start = strpos ($plainFile, "<meta", $offset)) {
		$offset = $start+1;
		$end = strpos ($plainFile, ">", $offset);
		$metaTags[$metaCount] = substr ($plainFile, $start, $end - $start+1);
		if (preg_match ("/<meta(:space:)*name(:space:)*=(:space:)*\"copyright\"/i",$metaTags[$metaCount])) {
			$copyright =  preg_replace ("/<meta(:space:)name(:space:)*=(:space:)*\"copyright\"(:space:)*content(:space:)*=\"/i", "", $metaTags[$metaCount]);
			$copyright = substr ($copyright, 0, strpos($copyright, "\""));
		}
		++$metaCount;
	}
}
// END REMOVE FOR BASIC -------------------------------------------------------------------

// REMOVE FOR BASIC -------------------------------------------------------------------
// REMOVE FOR WEBMASTER ---------------------------------------------------------------
function checklog($address) {
	
	global $urlToPrint;
	global $from;
	global $name;
	global $comment;
	global $maxip;	
	global $logfile;
	
	if (!isset ($REMOTE_ADDR)) {
		$ip = getenv("REMOTE_ADDR");
	}
	else {
		$ip = $REMOTE_ADDR;
	}
	$retval = true;
	$filename = $logfile."/web2printer".date("Ymd").".log";
	$filearray = @file ($logfile);
	
	if (is_array ($filearray)) {
		$counter = 0;
		$boundary = 0;
		while ($counter < count($filearray) && $retval == true) {
			if (eregi ($ip, $filearray[$counter])) {
				++$boundary;
			}
			if ($maxip < $boundary) {
				$retval = false;
				break;
			}
			++$counter;
		}
	}
	
	if ($retval == true) {
		$fp = @fopen ($filename, "a");
		@fputs ($fp, date ("d.m.Y h:m:s", time()). " ;".$urlToPrint." ;".$from." ;".$name." ;".$comment." ;".$address." ;".$ip."\n");
		@fclose ($fp);
	}
	return $retval;
}
// END REMOVE FOR BASIC ---------------------------------------------------------------
// END REMOVE FOR WEBMASTER -----------------------------------------------------------

// REMOVE FOR BASIC -------------------------------------------------------------------
// REMOVE FOR WEBMASTER ---------------------------------------------------------------
function SendMail () {
	
	global $copyright;
	global $title;
	global $content;
	global $urlToPrint;
	global $to;
	global $from;
	global $name;
	global $comment;
	global $email;
	global $linkCount;
	global $links;
	global $authentyPage;
	global $pageUrlHeader;
	global $pageXRefURLHeader;
	global $customFooter;
	global $web2printerVersion;
	global $pageXRefImages;
	global $footer; 	
	
	$ok = true;
	if (!eregi("^[a-z0-9_.-]+@[a-z0-9_.-]+\.[a-z]+$", trim($from))) {
		$ok = false;
	}
	$tok = strtok($to,",");
	$i = 0;
	while ($tok && $i < 5) {
		if (!eregi("^[a-z0-9_.-]+@[a-z0-9_.-]+\.[a-z]+$", trim($tok))) {
			$ok = false;
		}
		$tok = strtok(",");
        $i++;
	}
		
	// about if the email adresse not valid syntax
	if (!$ok) {
		echo $authentyPage;
		return;
	}
	$headers = "From: ".$from."\r\n";
	//specify MIME version 1.0
	$headers .= "MIME-Version: 1.0\r\n";
	//unique boundary
	$boundary = uniqid("web2printer");
	//tell e-mail client this e-mail contains//alternate versions
	$headers .= "Content-Type: multipart/alternative; boundary =".$boundary."\r\n\r\n";
	//message to people with clients who don't
	//understand MIME
	$headers .= "This is a MIME encoded message.\r\n\r\n";
	switch ($footer+2) {
		case 2: $finalfooter = "</table></body></html>"; break;
		case 3: $finalfooter = "<tr><td><font size=-1><p><hr size=1 noshade>This Page was generated with ".$web2printerVersion." in: ".get_current()." <a href=http://www.printer-friendly.com>http://www.printer-friendly.com</a><br></font></p></td></tr></table></body></html>"; break;
		case 5: $finalfooter = "<tr><td><p><hr size=1 noshade>".$customFooter."</p></td></tr></table></body></html>"; break;
	}
	//HTML version of message
	$headers .= "--$boundary\r\n"."Content-Type: text/html; charset=ISO-8859-1\r\nContent-Transfer-Encoding: base64\r\n\r\n";
		
	$body     ="<html><head><title>".$title."</title></head><body bgcolor=#ffffff>";
	$body     .= "<table summary=\"web2printer crossreference\"><tr><td>";
	if (strlen ($comment) > 1) {
		$body     .= "=============================================================<br>";
		$body     .= $comment;
		$body     .= "<br>=============================================================<br>";
	}
	$body     .= $content;
	$body     .= "</tr></td><tr><td><font size=-1>";
	$body     .= "<p><hr size=1 noshade>".$pageUrlHeader.makeUrl($urlToPrint)."</p>";
	$body     .= "</font></tr></td><tr><td><font size=-1>";
	if ( 0 < $linkCount) {
		$body     .= "<p>".$pageXRefURLHeader."<br>";
		for ($i = 0; $i < $linkCount; $i++) {
			$link = makeUrl($links[$i]);
			$body     .=  "[".($i+1)."] <a href=\"".$link."\">".$link."</a><br>";
		}
		$body     .=  "</font></tr></td><tr><td><font size=-1>";
	}
	if (strlen ($copyright) > 0) {
		$body     .= "<p><hr size=1 noshade><i>Copyright:".$copyright."</i><br>";
	}
	$body     .= $finalfooter;
	$headers .=  chunk_split(base64_encode($body));			
	//send message
	$tok = strtok($to,",");
	$i = 0;
	while ($tok && $i < 5) {
		mail($tok, "Your article:".$title, "", $headers);
		checklog($tok);
        $tok = strtok(",");
        $i++;
	}
	
	echo $authentyPage;
}
// END REMOVE FOR BASIC -------------------------------------------------------------------
// END REMOVE FOR WEBMASTER ---------------------------------------------------------------

// print the procceced file
function display() {
	
	global $content;
	global $urlToPrint;
	global $title;
	global $email;
	global $preserveMetaTags;
	global $metaCount;
	global $metaTags;
	global $pageUrlHeader;
	global $pageXRefURLHeader;
	global $customFooter;
	global $web2printerVersion;
	global $pageXRefImages;
	global $footer; 	
	
	echo  "<html><head>";
	
	// REMOVE FOR BASIC -------------------------------------------------------------------
	if (1 == $preserveMetaTags) {
		for ($i = 0; $i < $metaCount; $i++) {
			echo $metaTags[$i];
		}
	}
		
	// REMOVE FOR WEBMASTER ---------------------------------------------------------------
	if ($email == 1) {
		echo $content;
		return;
	}
	// END REMOVE FOR WEBMASTER -----------------------------------------------------------
	// END REMOVE FOR BASIC ---------------------------------------------------------------
	
	global $stylesheet;
	if (strlen($stylesheet) > 0) {
		echo "<link href=\"".$stylesheet."\" rel=\"stylesheet\" type=\"text/css\">";
	}
	
	
	// USE FOR BASIC ------- --------------------------------------------------------------
	// $finalfooter = "<tr><td><font size=-1><p><hr size=1 noshade>This Page was generated with ".$web2printerVersion." in: ".get_current()." <a href='http://www.printer-friendly.com'>http://www.printer-friendly.com</a><br></font></p></td></tr></table></body></html>"; 
	// END - USE FOR AND BASIC ------------------------------------------------------------
	
	// USE FOR AND WEBMASTER E-COMMERCE COMPANION -----------------------------------------
	switch ($footer+3) {
		case 3: $finalfooter = "</table></body></html>"; break;
		case 4: $finalfooter = "<tr><td><font size=-1><p><hr size=1 noshade>This Page was generated with ".$web2printerVersion." in: ".get_current()." <a href='http://www.printer-friendly.com'>http://www.printer-friendly.com</a><br></font></p></td></tr></table></body></html>"; break;
		case 5: $finalfooter = "<tr><td><p><hr size=1 noshade>".$customFooter."</p></td></tr></table></body></html>"; break;
	}
	// END USE FOR AND WEBMASTER E-COMMERCE COMPANION -------------------------------------
	
	echo "<title>".$title;
	echo "</title></head><body bgcolor=#ffffff><table summary='web2printer crossreference'><tr><td>";
	echo $content;
	echo "</tr></td><tr><td><font size=-1>";
	$link = makeUrl($urlToPrint);
	echo "<p><hr size=1 noshade>".$pageUrlHeader."<a href=\"".$link."\">".$link;
	echo "</a></p></font></tr></td>";
	// REMOVE FOR BASIC ---------------------------------------------------------------
	echo "<tr><td><font size=-1>";
	global $linkCount;
	global $links;
	if ( 0 < $linkCount) {
		echo $pageXRefURLHeader."<br>";
		for ($i = 0; $i < $linkCount; $i++) {
			$link = makeUrl($links[$i]);
			echo "[".($i+1)."] <a href=\"".$link."\">".$link."</a><br>";
		}
		echo "</font></tr></td><tr><td><font size=-1>";
	}
	// REMOVE FOR WEBMASTER ---------------------------------------------------------------
	global $imageCount;
	global $images;
	if ( 0 < $imageCount) {
		echo "<p><b>Images:</b><br>";
		for ($i = 0; $i < $imageCount; $i++) {
			echo "[".($i+1)."] ".makeUrl($images[$i])."<br>";
		}
	}
	
	echo "</font></tr></td>";
	// END REMOVE FOR WEBMASTER -----------------------------------------------------------
	// END REMOVE FOR BASIC ---------------------------------------------------------------
	global $copyright;
	if (strlen ($copyright) > 0) {
		echo "<tr><td><font size=-1><p><hr size=1 noshade><i>Copyright:".$copyright."</i><br></font></tr></td>";
	}
	echo $finalfooter;
}
?>
