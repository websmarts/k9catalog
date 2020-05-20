<?php 
$imagefolder='source/';
$thumbsfolder='source/';
$pics=directory($imagefolder,"jpg,JPG,JPEG,jpeg,png,PNG");
$pics=ditchtn($pics,"tn_");
if ($pics[0]!="")
{
//	echo"<pre>";
//	print_r($pics);
//	echo "</pre>\n";
//	exit;
	foreach ($pics as $p)
	{
		createthumb($imagefolder.$p,$thumbsfolder."tn_".$p,150,150);
	}
}

/*
	Function ditchtn($arr,$thumbname)
	filters out thumbnails
*/
function ditchtn($arr,$thumbname)
{
	foreach ($arr as $item)
	{
		if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
	}
	return $tmparr;
}

/*
	Function createthumb($name,$filename,$new_w,$new_h)
	creates a resized image
	variables:
	$name		Original filename
	$filename	Filename of the resized image
	$new_w		width of resized image
	$new_h		height of resized image
*/	
function createthumb($name,$filename,$new_w,$new_h)
{
	$system=explode(".",$name);
	echo "Name: ".strtolower($name)."<br>\n";
	if (preg_match("/jpg|jpeg/i",$system[1])){$src_img=imagecreatefromjpeg($name);}
	if (preg_match("/png/i",$system[1])){$src_img=imagecreatefrompng($name);}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) 
	{
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
//	  $destimg=ImageCreateTrueColor($new_width,$new_height) or die("Problem In Creating image"); 
//    $srcimg=ImageCreateFromJPEG($image_name) or die("Problem In opening Source Image"); 
//    ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die("Problem In resizing"); 			
//    ImageJPEG($destimg,$dir."/".$thumb_dir."/".$file) or die("Problem In saving"); 

	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
//	ImageCopyResized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	if (preg_match("/png/",$system[1]))
	{
		imagepng($dst_img,strtolower($filename) ); 
	} else {
		imagejpeg($dst_img,strtolower($filename)); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

/*
        Function directory($directory,$filters)
        reads the content of $directory, takes the files that apply to $filter 
		and returns an array of the filenames.
        You can specify which files to read, for example
        $files = directory(".","jpg,gif");
                gets all jpg and gif files in this directory.
        $files = directory(".","all");
                gets all files.
*/
function directory($dir,$filters)
{
	$handle=opendir($dir);
	$files=array();
	if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
	if ($filters != "all")
	{
		$filters=explode(",",$filters);
		while (($file = readdir($handle))!==false)
		{
			for ($f=0;$f<sizeof($filters);$f++):
				$system=explode(".",$file);
				if ($system[1] == $filters[$f]){$files[] = $file;}
			endfor;
		}
	}
	closedir($handle);
	return $files;
}
?>
