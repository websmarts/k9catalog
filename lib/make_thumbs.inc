<? 

$new_width=100;  //Image width Change if needed 
$new_height=100;  //Image height Change if needed 
$thumb_dir="thumb";     			//Dir name where thumbs are stored in each folder 


function make_thumbnails($dir) {
 		global $thumb_dir;
    global $dir; 
    global $file;
    global $new_width; 
    global $new_height; 
    
	
	
	
	$res = do_query("SELECT * FROM images");  //Query 
	
if (is_array($res) ) {
	foreach ($res as $row) 	{ 
	 
	
	    $image_name = $row["path"];  			//Image path retrived 
			$dir = dirname($row["path"]); 		// eg dir = path/to/file/dir/
			if ($dir ==".") {$dir = "";}			// dirname() returns "." if path is just filename i.e no "/"s
			$file = basename($row["path"]); 	// eg file = image.jpg
	
	    //Identifying Image type 
	    $len = strlen($file); 
	    $pos =strrpos($file,"."); 
	    $filetype = strtolower(substr($file,$pos + 1,$len));
	    echo "file=$file - filetype=$filetype - dir=$dir <br>\n";
	    
	    echo "Checking thumb directory exists... ".$dir."/".$thumb_dir."<br>\n";
			if (!is_dir($dir."/".$thumb_dir)) {// check if thumbs dir exists in folder
				// create thumbs dir if it doesnt exist
				echo "Making thumb directory ".$dir."/".$thumb_dir."<br>\n";
				mkdir($dir."/".$thumb_dir);			
			}
	
	    if ( $filetype=="jpeg" || $filetype=="jpg") 
	    { 
	        thumb_jpeg ($image_name); //Call to jpeg function 
	    } 
	    else if($filetype="png" || $filetype="PNG") 
	    { 
	        thumb_png ($image_name);    //Call to PNG function 
	    } 
	
	    echo "<b>Done........".++$n."</b><br>\n"; 
	         
	}
} 
}

//JPEG function 
function thumb_jpeg($image_name) 
{ 
  
    global $thumb_dir;
    global $dir; 
    global $file;

    $new_width = 100; 
    $new_height = 100; 
    
    // this block makes sure the thumb has the same aspect ratio
    $image     = imagecreatefromjpeg($image_name);
    $img_x = imagesx($image);
    $img_y = imagesy($image);
    
    $aspect_ratio = ($img_x /$img_y);
    
    echo "Aspect ratio = $aspect_ratio => $img_x (x) -> $img_y (y) <br>\n";
    
    if($aspect_ratio < ($new_width/$new_height)){
    	$new_width = $new_height*$aspect_ratio;
    } elseif($aspect_ratio > ($new_width/$new_height)) {
    	$new_height = $new_width/$aspect_ratio;
    }

		echo "Thumb size:(W) X (H) $new_width X $new_height <br>\n";
		 
    $destimg=ImageCreateTrueColor($new_width,$new_height) or die("Problem In Creating image"); 

    $srcimg=ImageCreateFromJPEG($image_name) or die("Problem In opening Source Image"); 

    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 
		
	
			
    ImageJPEG($destimg,$dir."/".$thumb_dir."/".$file) or die("Problem In saving"); 
} 

//PNG function 
function thumb_png($image_name) 
{ 
    global $thumb_dir;
    global $dir; 
    global $file;

    global $new_width; 
    global $new_height; 
    
    // this block makes sure the thumb has the same aspect ratio
    $image     = imagecreatefrompng($image_name);
    $aspect_ratio = (imagesx($image)/imagesy($image));
    if($aspect_ratio < ($new_width/$new_height)){$new_width = $new_height*$aspect_ratio;
    } elseif($aspect_ratio > ($new_width/$new_height)) {
    	$new_height = $new_width/$aspect_ratio;
    }

    $destimg=ImageCreateTrueColor($new_width,$new_height) or die("Problem In Creating image"); 

    $srcimg=ImageCreateFromPNG($image_name) or die("Problem In opening Source Image"); 

    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 
		
	
			
    ImagePNG($destimg,$dir."/".$thumb_dir."/".$file) or die("Problem In saving"); 
} 

?> 
