<?php
  
    //mysql_query("SET NAMES utf8");
    
    // get documentary by id
    $doc = $docsDB->getMovieByID($id);
    
    // resize cover image
    include '../imgResize.php';
    
    // display image if one exists
    $imgURL = "../images/covers/". $doc['cover'];
    $imgThumb = "../images/covers/thumbs/". $doc['cover'];
    $thumbExists = file_exists($imgThumb);
    if($thumbExists) $thumbKb = round((filesize($imgThumb) / 1024), 2);
    
    $newSize = 300;
    
    if ($doc['cover'] && file_exists($imgURL)){
      $imgSize = getimagesize($imgURL);
      $imgRatio = round($newSize/$imgSize[0], 2);
      $imgSizeSmall = imageResize($imgSize[0], $imgSize[1], $newSize) ;
      $imgKb = round((filesize($imgURL) / 1024), 2);
    }else{
      $imgRatio = 0;
      $imgSize = Array(0,0);
      $imgSizeSmall = Array(0,0);
      $imgKb = 0;
    }

?>