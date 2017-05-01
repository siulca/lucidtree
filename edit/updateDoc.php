<?php

    // turn on debugging mode
    error_reporting(E_ALL);
    ini_set('display_errors', True);
    

$error = "";
$successArr = array();

include "../htmlFunctions.php";
//include '../opendb.phps';

require_once('../DBController.phps');
$docsDB = new DBController();

if(empty($_POST['title'])){
    $error = "Title can't be empty.";
}else if(empty($_POST['category'])){
    $error = "You must selected at least one category.";
}else if(!empty($_POST['id'])){
    if($_POST['id'] == "new"){
        $_POST['slug'] = formatURL($_POST['title']);
        
        if(strlen($_POST['slug']) > 2){
            // ensure slug/title is unique and doesn't exist yet
            $sql = "SELECT id,slug FROM docs WHERE slug = '". $_POST['slug'] ."'";
            $sameSlug = $docsDB->db()->query($sql);
            
            if (mysqli_num_rows($sameSlug) > 0){
                $row = $sameSlug->fetch_array();
                $error = "Title/slug already exists in documentary [". $row["id"] ."]. <br/>Change the <u>Title</u> and try again.";
            }else{
                createDoc($docsDB);
            }
        }else{
            $error = "Slug must be bigger than 2 chars [".$_POST['slug']."]";
        }
        
    }else{
        // upload image if one exists
        if(!empty($_FILES["file"]["type"])) uploadImage();
        updateDoc($docsDB);
    }
}

$docsDB->close();

if(!empty($error)) showError($error);

function createDoc($docsDB){
    
    $sql = "INSERT INTO docs (title,slug,subtitle,featured,isFree,visible,description,year,totalMins,cover,URLwebsite,URLtrailer,URLwatch,category)
    VALUES ('". $docsDB->db()->real_escape_string($_POST['title']) ."'
    ,'". $docsDB->db()->real_escape_string($_POST['slug'])."'
    ,'". $docsDB->db()->real_escape_string($_POST['subtitle'])."'
    ,'". $docsDB->db()->real_escape_string(isset($_POST['featured']) ? 1 : 0)."'
    ,'". $docsDB->db()->real_escape_string(isset($_POST['free']) ? 1 : 0)."'
    ,'". $docsDB->db()->real_escape_string(isset($_POST['visible']) ? 1 : 0)."'
    ,'". $docsDB->db()->real_escape_string($_POST['desc']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['year']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['mins']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['cover']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['official']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['trailer']). "'
    ,'". $docsDB->db()->real_escape_string($_POST['watch']). "'
    ,'". (isset($_POST['category']) ? $docsDB->db()->real_escape_string(implode(",", $_POST['category'])) : '') ."'
    )";
    
    //$result = mysql_query($sql) or die("Error: ". mysql_error(). " ||| With query: ". $sql);
    $result = $docsDB->db()->query($sql);
    $rowID = mysqli_insert_id($docsDB->db());
    
    // if there's no error then it's saved
    //header("Location: editDoc.php?id=". $rowID);
    
    $message = "<p class='message success'>Movie was created successfully.</p>";
    echo json_encode(array('id' => $rowID, 'message' => $message));
}

function updateDoc($docsDB){
    
    $sql = "UPDATE docs
    SET title = '". $docsDB->db()->real_escape_string($_POST['title']). "',
    subtitle = '". $docsDB->db()->real_escape_string($_POST['subtitle']). "',
    featured = '". $docsDB->db()->real_escape_string(isset($_POST['featured']) ? 1 : 0) ."',
    isFree = '". $docsDB->db()->real_escape_string(isset($_POST['free']) ? 1 : 0) ."',
    visible = '". $docsDB->db()->real_escape_string(isset($_POST['visible']) ? 1 : 0) ."',
    description = '". $docsDB->db()->real_escape_string($_POST['desc']). "',
    year = '". $docsDB->db()->real_escape_string($_POST['year']). "',
    totalMins = '". $docsDB->db()->real_escape_string($_POST['mins']). "',
    cover = '". $docsDB->db()->real_escape_string($_POST['cover']). "',
    URLwebsite = '". $docsDB->db()->real_escape_string($_POST['official']). "',
    URLtrailer = '". $docsDB->db()->real_escape_string($_POST['trailer']). "',
    URLwatch = '". $docsDB->db()->real_escape_string($_POST['watch']). "'".
    (isset($_POST['category']) ? ",category = '". $docsDB->db()->real_escape_string(implode(",", $_POST['category'])) ."'" : "") ."
    WHERE id = '". $_POST['id']. "'";
    
    //echo "saving--->". $sql;
    
    $result = $docsDB->db()->query($sql);
    
    // if there's no error then it's saved
    //header("Location: editDoc.php?id=". $_POST['id']);
    
    $message = '<p class="message success">Movie was saved successfully.</p>';
    echo json_encode(array('id' => $_POST['id'], 'message' => $message));
}

function uploadImage(){
    $maxKb = 200;
    $destination = "../images/covers/";
    
    //echo sys_get_temp_dir(). " | File: ". print_r($_FILES["file"]);
    
    if (($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png")
    || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg"))
    {
        if ($_FILES["file"]["size"] < $maxKb*1024)
        {
            if ($_FILES["file"]["error"] == 0)
            {
                // apend random number for TESTING ONLY
                //$_FILES["file"]["name"] = rand() . $_FILES["file"]["name"];
                
                $filePath = $destination. $_FILES["file"]["name"];
                if (!file_exists($filePath))
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
                    
                    try{
                        createThumbnail($filePath);
                    }catch(Exception $e){
                        showError("Problem creating thumbnail: ". $e->getMessage());
                    }
                    
                    // set the post cover property so it gets saved to database
                    $_POST['cover'] = $_FILES["file"]["name"];
                }else{
                    showError("File <u>". $_FILES["file"]["name"] . "</u> already exists.<br/>Please rename the file and try again.");
                }
            }else{
                showError( "Can't upload file because: " . $_FILES["file"]["error"]);
            }
        }else{
            showError( "File is too large <u>".round($_FILES["file"]["size"]/1024)." kB</u>.<br/>Max file size is <u>". $maxKb ." kB</u>");
        }
    }else{
        showError( "Invalid file type <u>".$_FILES["file"]["name"]."</u>!<br/>File must be a <u>gif/jpg/png</u> image." );
    }
}

function createThumbnail($filePath){
    
    require_once '../thumbnailer/paGdThumbnail.php';

    // thumbnail configuration
    $thumb_width = 183;
    // height will be redefined based on scale
    //$thumb_height = 100;
    $thumb_method = 'crop';
    $thumb_bgColour = null;//array(255,255,240);
    $thumb_quality = 70;
    
    $destination = "../images/covers/thumbs/";
    
    $fileParts = pathinfo($filePath);
    $thumb_file = $destination . $fileParts['filename'] . '.jpg';
    
    // create a gd image. reading the contents of the image file into a string, then
    // using imagecreatefromstring saves having to check the filetype and which 
    // imagecreatefrom(jpeg/gif/png) function to use
    $image = imagecreatefromstring(file_get_contents($filePath));
    
    // calculate height based on with to keep aspect ratio
    $imgSize = getimagesize($filePath);
    $percentage = ($thumb_width / $imgSize[0]);
    $thumb_height = round($imgSize[1] * $percentage);
    
    if( $image ){
        // create the thumbnail
        $thumb = paGdThumbnail($image, $thumb_width, $thumb_height, $thumb_method, $thumb_bgColour);
        
        // free the image resource
        imagedestroy($image);
        
        if( $thumb ){
            // save the thumbnail
            if( imagejpeg($thumb, $thumb_file, $thumb_quality) ){
                $has_thumb = true;
            }else{
                showError("Could not create thumbnail: ". $thumb_file. " : " . $thumb);
            }
            
            // free the memory used by the thumbnail image
            imagedestroy($thumb);
        }
    }
}

function showError($errorMsg){
    $doc = Array( "id" => $_POST['id'] );
    if (!empty($_POST['title'])) $doc["title"] = $_POST['title'];
    if (!empty($_POST['subtitle'])) $doc["subtitle"] = $_POST['subtitle'];
    if (!empty($_POST['featured'])) $doc["featured"] = (isset($_POST['featured']) ? 1 : 0);
    if (!empty($_POST['free'])) $doc["isFree"] = (isset($_POST['free']) ? 1 : 0);
    if (!empty($_POST['visible'])) $doc["visible"] = (isset($_POST['visible']) ? 1 : 0);
    if (!empty($_POST['desc'])) $doc["description"] = $_POST['desc'];
    if (!empty($_POST['year'])) $doc["year"] = $_POST['year'];
    if (!empty($_POST['mins'])) $doc["totalMins"] = $_POST['mins'];
    if (!empty($_POST['cover'])) $doc["cover"] = $_POST['cover'];
    if (!empty($_POST['official'])) $doc["URLwebsite"] = $_POST['official'];
    if (!empty($_POST['trailer'])) $doc["URLtrailer"] = $_POST['trailer'];
    if (!empty($_POST['watch'])) $doc["URLwatch"] = $_POST['watch'];
    if (!empty($_POST['category'])) $doc["category"] = implode(",", $_POST['category']);
    
    $message = "<div class='message fail'>".$errorMsg."</div>";
    echo json_encode(array('message' => $message));
    
    exit();
}
?>