<ul class='menuList'>
<?php
    // add sort menu
    $i = 1;
    foreach ($sortArr as $menuItem => $value)
    {
        /*if($_SESSION['sortBy'] == $value){
            $html .= "<li class='selectedMenuItem'>".$menuItem."</li>";
        }else{*/
            echo "\n<li>". getMenuLink($menuItem, "sortForm".$i++, "sortBy", $value) ."</li>";
        //}
    }
?>
</ul>

<div class='menuPadding'></div>

<ul class='menuList'>
<?php
    // add direction of sort menu
    //if($_SESSION['sortDir'] == "ASC"){
        //$html .= "<li class='selectedMenuItem'>Ascending</li>";
    //}else{
        echo "\n<li>". getMenuLink("Ascending", "dirFormA", "sortDir", "ASC") ."</li>";
        echo "\n<li>". getMenuLink("Descending", "dirFormD", "sortDir", "DESC") ."</li>";
        //$html .= "<li class='selectedMenuItem'>Descending</li>";
    //}
?>
</ul>