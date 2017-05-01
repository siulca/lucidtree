<?php
  
  // start unique session array
  if (!isset($_SESSION)) {
    session_start();
  }
  
  // turn on debugging mode
  error_reporting(E_ALL);
  ini_set('display_errors', True);
  
  //print_r($_POST);
  
  // store post variables in session
  if (!empty($_POST['category'])) {
    $_SESSION['category'] = $_POST['category'];
  }
  if (isset($_POST['sortBy'])) {
    $_SESSION['sortBy'] = $_POST['sortBy'];
  }
  if (isset($_POST['sortDir'])) {
    $_SESSION['sortDir'] = $_POST['sortDir'];
  }
  if (isset($_POST['view'])) {
    $_SESSION['view'] = $_POST['view'];
  }
  if (isset($_POST['docID'])) {
    $_SESSION['docID'] = $_POST['docID'];
  }
  
  
  //// DEFAULTS /////
  
  
  // array of sorting types with label on left and DB table column name on right
  $sortArr = array(
      'By title' => 'title',
      'By release year' => 'year',
      'By running time' => 'totalMins',
      'By date added' => 'createdOn',
      'By free' => 'isFree'
  );
  
  // default sorting value
  if(!isset($_SESSION['sortBy'])){
    $sortKeys = array_values($sortArr);
    $_SESSION['sortBy'] = $sortKeys[0];
  }
  
  // default sorting direction (null = ascending)
  if(!isset($_SESSION['sortDir'])) $_SESSION['sortDir'] = "ASC";
  
  // default category
  if(!isset($_SESSION['category'])) $_SESSION['category'] = "all";
  
  // default view (null = list/detailed view)
  if(!isset($_SESSION['view'])) $_SESSION['view'] = 0;
  
  
  //header("Location: index.php");

?>