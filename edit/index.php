<?php
// turn on debugging mode
error_reporting(E_ALL);
ini_set('display_errors', True);

include_once 'admin-class.php';
$admin = new itg_admin();
$admin->_authenticate();

require_once('../DBController.phps');
$docsDB = new DBController();

// open database
//include '../opendb.phps';

// get all documentaries in table and sorted
//$sql = "SELECT * FROM docs ORDER BY id DESC";
$result = $docsDB->getAllMovies(); //mysql_query($sql) or die("Error: ". mysql_error(). " with query ". $sql);

$minutes = 0;
while($row = $result->fetch_array()) {
    if(!empty($row["URLwatch"])) $minutes += $row['totalMins'];

}

$d = floor ($minutes / 1440);
$h = floor (($minutes - $d * 1440) / 60);
$m = $minutes - ($d * 1440) - ($h * 60);

// rewind result index
mysqli_data_seek($result, 0);

//echo "output:". mysql_num_rows($result);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <title>Lets edit some moves</title>
        <link rel='stylesheet' href='../css/admin.css' type='text/css'>
        <link rel='stylesheet' href='../css/buttons.css' type='text/css'>
        
        <script src="../js/jquery-1.7.2.min.js" type="text/javascript"></script>
        <script src="../js/video-validation.js" type="text/javascript"></script>
    </head>
    <body>
        <div class='contentAdmin'>
            <div class="topBar">
                <a href='editDoc.php?id=new' class="button medium white">+ Add movie</a>
                <a href='/' class="button medium green" target="_blank">Live view</a>
                <a id='ckeckLinksBtn' href='#' class="button medium red" >Check links</a>
                <a href='logout.php' class="button medium black right">Logout</a>
            </div>
            <fieldset id='login'>
                <legend>Welcome, <?php echo $admin->get_nicename() .". You have ". mysqli_num_rows($result); ?> movies.</legend>
                
                <?php echo "{$d} days {$h} hours {$m} mins of mind expanding entertainment." ?>
                
                <table class="docsList">
                    <tr>
                        <th>ID</th><th>Slug</th><th>Title</th><th>Year</th><th>Mins</th><th>Categories</th><th>Free</th><th>W</th><th>T</th><th>V</th><th>Feature</th><th>Visible</th>
                    </tr>
                    
                    <?php
                    // display all rows
                    $alternate = 1;
                    while($row = $result->fetch_array())
                    {
                      $editLink = "editDoc.php?id=". $row['id'];
                      
                      echo "<tr class='movieRow' bgcolor='#". (($alternate==1) ? "e4e4e4": "f4f4f4") ."' onclick='document.location=\"". $editLink ."\";'>";
                      echo "<td><b>". $row['id'] ."</b></td>";
                      echo "<td><a href='". $editLink ."'>". $row['slug'] ."</a></td>";
                      echo "<td><b><a href='". $editLink ."'>". $row['title'] ."</a></b></td>";
                      echo "<td>". $row['year'] ."</td>";
                      echo "<td>". $row['totalMins'] ."</div></td>";
                      echo "<td>". $row['category'] ."</td>";
                      echo "<td class='". (($row['isFree']==1)? "green": "") ."'></td>";
                        
                      $www = !empty($row['URLwebsite']) ? "<input type=hidden name=website value='". $row['URLwebsite'] ."'>" : "";
                      echo "<td class='". (!empty($row['URLwebsite'])? "green": "red") ."'>". $www ."</td>";
                      
                      $trailer = !empty($row['URLtrailer']) ? "<input type=hidden name=vid value='". $row['URLtrailer'] ."'>" : "";
                      echo "<td class='". (!empty($row['URLtrailer'])? "green": "red") ."'>". $trailer ."</td>";
                        
                      $video = !empty($row['URLwatch']) ? "<input type=hidden name=vid value='". $row['URLwatch'] ."'>" : "";
                      echo "<td class='". (!empty($row['URLwatch'])? "green": "red") ."'>". $video ."</td>";
                        
                      echo "<td class='". (($row['featured']==1)? "green": "") ."'></td>";
                      echo "<td class='". (($row['visible']==1)? "green": "black") ."'></td>";
                      echo "</tr>";
                      
                      $alternate = -$alternate;
                    }
                    ?>
                    
                    </table>
            </fieldset>
        </div>
    </body>
</html>

<?php

// close database
$docsDB->close();

?>