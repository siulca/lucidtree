<ul class='menuList'>
<?php
    
    $viewArr = array("Detailed view",
                     "List view",
                     "Artwork view");
  
    // add view menu
    
    for ($i=0; $i<count($viewArr); $i++)
    {
        /*if($_SESSION['view'] == $i){
            $html .= "<li class='selectedMenuItem'>".$viewArr[$i]."</li>";
        }else{*/
            echo "\n<li>". getMenuLink($viewArr[$i], "displayForm".$i, "view", $i) ."</li>";
        //}
    }
?>
</ul>