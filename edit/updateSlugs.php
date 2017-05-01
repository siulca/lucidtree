<?php
    
    // turn on debugging mode
    error_reporting(E_ALL);
    ini_set('display_errors', True);
    
    include "../htmlFunctions.php";
    
    // open database
    include '../opendb.phps';
    
    // get documentary by id
    $sql = "SELECT id, title, slug FROM docs WHERE slug=''";
    $result = mysql_query($sql) or die("Error: ". mysql_error(). " with query ". $sql);
    
    //print_r($doc);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      
      // generate new slug from documentary title
      $newSlug = formatURL($row["title"]);
      
      // ensure it's unique and doesn't exist yet
      $sql = "SELECT slug FROM docs WHERE slug = '". $newSlug ."'";
      $sameSlug = mysql_query($sql);
      
      if (mysql_num_rows($sameSlug) > 0){
        printf("<br/><font color='#ff2200'>ID: %s  |  Title: %s  |  <b> >>>> NOT SAVED <<<< SLUG ALREADY EXISTS: %s</b></font>", $row["id"], $row["title"], $newSlug);
      }else{
        
        // save new slug
        $sql = "UPDATE docs SET slug = '". $newSlug ."' WHERE id ='". $row["id"] ."'";
        $savingResult = mysql_query($sql) or die("Error: ". mysql_error(). " with query ". $sql);
        
        printf("<br/>ID: %s  |  Title: %s  |  Saved new slug: <b>%s</b>", $row["id"], $row["title"], $newSlug);
      }
      
    }
    
    printf("<br/><br/><br/>");
    
    // close database
    include '../closedb.phps';
?>