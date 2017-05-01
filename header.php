<?php

// get random quote header
    if (!isset($_SESSION['slogan'])) {
        //$db->query("SET NAMES utf8");
        $result = $db->getRandomQuote();
        $_SESSION['slogan'] = $result->fetch_array();
    }
  
    // add header
    echo '<div class="smallText version">Beta</div>';
    echo '<div class="header"><img id="logo" src="images/lucidTree5.png">';
    echo '<br/><span id="quote">"'. $_SESSION['slogan']['quote'] .'"</span><br/><span id="quoteAuthor">'. $_SESSION['slogan']['author'] .'</span></div>';

?>
