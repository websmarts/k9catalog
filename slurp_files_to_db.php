<?php

require("lib/db.inc");
require("lib/common.inc");
require("lib/make_thumbs.inc");


$allowable_file_types = array("jpg","jpeg");


// show directory content
function doDir($dir="source", $i=0, $maxDepth=1){
	global $allowable_file_types;
	
	//taint all images in database so we know which to delete after update
	do_query("UPDATE images set taint=1 where path like'$dir%'");
	
	
	$i++;
	if($checkDir = opendir($dir)){
       $cDir = 0;
       $cFile = 0;
       // check all files in $dir, add to array listDir or listFile
       while($file = readdir($checkDir)){
           if($file != "." && $file != ".." && $file != "thumb"){
               if(is_dir($dir . "/" . $file)){
                   $listDir[$cDir] = $file;
                   $cDir++;
               } else{
           		// check file type is supported
           		$path_parts = pathinfo($file);
           		echo dumper($path_parts);
           		
           		$type = strtolower($path_parts['extension']);
           		echo "filetype= $type<br>\n";
             		if (in_array($type,$allowable_file_types)) {
             			echo " filetype supported<br>\n";
                 	$listFile[$cFile] = $file;
                 	$cFile++;
                }
             }
        	}
    	}
    
       
       // show directories
       if(count($listDir) > 0){
           sort($listDir);
           echo "<TABLE>\n";
           for($j = 0; $j < count($listDir); $j++){
               echo "
               <tr>";
                   $spacer = "";
                   for($l = 0; $l < $i; $l++) $spacer .= "&nbsp;";
                   // create link
                   $link = "<a href=\"" . $_SERVER["PHP_SELF"] . "?dir=" . $dir . "/" . $listDir[$j] . "\">$listDir[$j]</a>";
                   echo "<td>" . $spacer . $link. "</td>
               </tr>";
               // list all subdirectories up to maxDepth
               if($i < $maxDepth) doDir($dir . "/" . $listDir[$j], $i, $maxDepth);
           }
           echo "</table>\n";
       }
       
       // show files
       if(count($listFile) > 0){
           sort($listFile);
           echo "<TABLE>\n";
           for($k = 0; $k < count($listFile); $k++){
               $spacer = "";
               for($l = 0; $l < $i; $l++) $spacer .= "&nbsp;";
                
                $path = addslashes($dir."/".$listFile[$k]);
               
                // Check if this path exists in the database already
                $sql = "select *  from images where path='".$path."'";
                $res = do_query($sql);
                if (!count($res) > 0) {
                	$sql = "INSERT INTO images SET path='".$path."'";
                	$res = do_query($sql);
                } else { // already in database so just remove taint
                	do_query("UPDATE images set taint=0 where path='$path'");
                }
                
               echo "
               <tr>
                   <td>" . $spacer . $listFile[$k] . "<BR>".$sql ."</td>
               </tr>";    
           }
           echo "</table>\n";
       }        
       closedir($checkDir);
   }
}



/*
 *
 * MAIN
 *
 *
 */
 if (!isSet($_GET['dir']) ) {
	$dir = "source";// default top resource directory 
	} else {
		$dir = $_GET['dir'];
	}

 
doDir($dir,0,4);// Dir, starting depth , $maxdepth  to descend



// get all the tainted records and delete any orphan thumbnail images
$res = do_query("SELECT path FROM images where taint=1 and path like'".$_GET['dir']."%'");
if (is_array($res) ) {
	foreach ($res as $file) {
		echo "<pre>\n";
		print_r($file);
		echo "</pre>\n";
		
		$path_parts = pathinfo($file['path']);
		echo "unlinking ".$path_parts['dirname']."/thumb/".$path_parts['basename']."<br>\n";
		unlink($path_parts['dirname']."/thumb/".$path_parts['basename']);
	}
}




// delete all tainted records
do_query("DELETE FROM images where taint=1 and path like'".$_GET['dir']."%'");

make_thumbnails($dir);


?>