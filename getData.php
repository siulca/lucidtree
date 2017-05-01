<?php

    /*** FUNCTIONS CALLED BY AJAX ***/

    // turn on debugging mode
    error_reporting(E_ALL);
    ini_set('display_errors', True);
  
    // includes
    include "updateState.php";
    include 'imgResize.php';
    include "htmlFunctions.php";

    require_once('DBController.phps');
    
    if (isset($_POST['data'])) {
        
        if($_POST['data'] == 'contentByCategory'){
            echo contentByCategory();
        }else if($_POST['data'] == 'embedCodeByID'){
            echo embedCodeByID();
        }
    }

    function contentByCategory(){
        // new db connection
        $db = new DBController();
        $result = $db->getContentByCategory($_SESSION['category'], $_SESSION['sortBy'], $_SESSION['sortDir']);

        // return json object
        $rows = array();
        while($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        
        // close database
        $db->close();
        
        return json_encode($rows);
    }

    // returns the embed code for a video or trailer of given video ID
    function embedCodeByID(){

        if (isset($_POST['id'])) {

            if(isset($_POST['type']) && $_POST['type']=="watch"){
                $colName = "URLwatch";
            }else if($_POST['type']=="trailer"){
                $colName = "URLtrailer";
            }

            if(isset($colName)){
                $db = new DBController();
                $result = $db->getEmbedCodeByID($_POST['id'], $colName);
                $db->close();

                return $result;
            }else{
                echo "Error: unknown type.";
            }
        }else{
            echo "Error: no given ID.";
        }
    }
?>
