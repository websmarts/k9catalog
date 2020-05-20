<?php





// show directory content
function doDir($dir="source", $i=0, $maxDepth=1){
		
	
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
               }
               else{
                   $listFile[$cFile] = $file;
                   $cFile++;
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
                
                
               echo "
               <tr>
                   <td>" . $spacer . $listFile[$k]  ."</td>
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

 
doDir($dir,0,1);// Dir, starting depth , $maxdepth  to descend




//make_thumbnails($dir);


?>