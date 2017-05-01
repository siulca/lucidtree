<div class="featuredRow round">
    <div id="featuredTitle">Featured <span class="pageNum"></span></div>
    <div id="player"></div>
    <div class="sliderBtn sliderPrevious"><div class='label'>&#10092;</div></div>
    <div class="sliderBtn sliderNext"><div class='label'>&#10093;</div></div>
    <div class="sliderMask">
        <ul id="featuredList" class="sliderContent">

<?php
    // get random movie to be featured
    //if (!isset($_SESSION['featured'])) {
            
      //$sql = $mysqli->prepare("SELECT * FROM docs WHERE featured=1 AND visible=1 AND cover<>'' ORDER BY createdOn DESC");
      //$sql->execute();
      $result = $db->getFeaturedMovies();//$sql->get_result();
      //$result = $mysqli->query($sql) or die("Error: ". mysqli_error($mysqli). " with query ". $sql);
    
      //$_SESSION['featured'] = mysql_fetch_array($result);
    //}
    
    //$row = $_SESSION['featured'];
    // list of featured movies
    $html = '';

    // display all rows
    while($row = $result->fetch_array())
    {
      // display image if one exists
      $imgURL = "images/covers/thumbs/". $row['cover'];
      
      if ($row['cover'] && file_exists($imgURL))
      {
        //$imgSize = getimagesize($imgURL);
        //$imgSizeSmall = imageResize($imgSize[0], $imgSize[1], 120);
        
        $html .= '<li>';
        $html .= '<a id="movie" href="'.formatURL($row['title']) .'" rel="'.$row['id'].'"><span id="coverHolder"><img id="cover" src="'. $imgURL .'" alt="Click to watch '. $row['title'] .' now."></span>';
        $html .= '<br/><span class="">'.$row['title'].'</span></a>';
        $html .= '</li>';
      }
    }
    
    echo $html;
?>
        </ul>
    </div>
</div>