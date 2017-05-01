<?php

    // turn on debugging mode
    error_reporting(E_ALL);
    ini_set('display_errors', True);
    
    include_once 'admin-class.php';
    $admin = new itg_admin();
    $admin->_authenticate();
    
    require_once('../DBController.phps');
    $docsDB = new DBController();

    //trace all vars
    //foreach($_GET as $vblname => $value) echo 'GET:'. $vblname . ' = ' . $value . "<br />\n";
    //foreach($_POST as $vblname => $value) echo 'POST:'. $vblname . ' = ' . $value . "<br />\n"; 
    
    // load page only if ID supplied
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }else if(isset($_POST['id'])){
        $id = $_POST['id'];
    }else{
        die("Error: You must specify a valid doc ID to edit.");
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <link rel='stylesheet' href='../css/admin.css' type='text/css'>
        <link rel='stylesheet' href='../css/buttons.css' type='text/css'>
        <title>Edit movie</title>
        
        <script src="../js/greensock/TweenMax.min.js" type="text/javascript"></script>
        <script src="../js/greensock/plugins/CSSPlugin.min.js" type="text/javascript"></script>
        <script src="../js/greensock/easing/EasePack.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class='contentAdmin'>
            <div class="topBar">
                <a href='index.php' class="button medium white"><< Back</a>
                <a href='/' class="button medium orange" target="_blank">Live view</a>
                <a href='logout.php' class="button medium black right">Logout</a>
            </div>
    
<?php
    
    // check if saving form
    //if ($_POST) include "updateDoc.php";
    
    // only load doc if saved successfully
    if(!isset($error)) include "loadDoc.php";
    
    $categories = $docsDB->getCategories();
    $docsDB->close();
    
    $pageTitle = "Edit: ". $doc['title'];
    
    // render forms
    //include 'uploadForm.php';
    include "docForm.php";
?>

        </div>
    </body>
</html>
