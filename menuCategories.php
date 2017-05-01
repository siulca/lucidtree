<?php

    require_once('DBController.phps');

    // store the total count of documentaries
    if(empty($categoryFilter)){
      $_SESSION['totalDocs'] = mysqli_num_rows($result);
    }else if(!isset($_SESSION['totalDocs'])){
      $_SESSION['totalDocs'] = "-";
    }
  
    // load all documentaries selected categories
    $db = new DBController();
    $catsResult = $db->getCategoriesCount();
    $catsArr = mysqli_fetch_array($catsResult);
    $catsArr = explode(",", $catsArr[0]);
    $catsCount = array_count_values($catsArr);
    
    // load total doc count
    $result = $db->getMovieCount();
    $countArr = $result->fetch_row();
    $_SESSION['totalDocs'] = $countArr[0];
    
    // load category names
    $categories = $db->getCategories();

    // SET HTML
    $html = "\n\n<ul class='menuList'>";
    $html .= "\n<li>". getMenuLink('All movies', 'catFormAll', 'category', 'all');
    $html .= getCatCount($_SESSION['totalDocs']) ."</li>";
    
    // add indifidual category filters
    while($row = $categories->fetch_array())
    {
        $html .= "\n<li>". getMenuLink($row['category'], 'catForm'.$row['id'], 'category', $row['id']);
        $html .= getCatCount($catsCount[$row['id']]) ."</li>";
    }
    
    echo $html ."</ul>\n";
    
    
    function getCatCount($catCount){
        return "<span class='smallText grey'>". $catCount ."</span>";
    }
?>