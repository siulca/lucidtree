<?php
  // start unique session array
  if (!isset($_SESSION)) {
    session_start();
  }
?>

<!DOCTYPE HTML>
<html>
<head>
  <title>Lucid Tree: Movie library</title>
  <meta name="keywords" content="watch, free, online, best, top, documentaries, movies, films, trailers, awareness" />
  <meta name="description" content="Lucid Tree is a free online movie library of must watch documentaries for inquisitive minds seeking a better understanding of the world and beyond."/>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <link rel='stylesheet' href='css/main.css' type='text/css'>
  <link rel='stylesheet' href='css/views.css' type='text/css'>
  <link rel='stylesheet' href='css/buttons.css' type='text/css'>
  
  <script src="js/greensock/TweenMax.min.js" type="text/javascript"></script>
  <script src="js/greensock/plugins/CSSPlugin.min.js" type="text/javascript"></script>
  <script src="js/greensock/easing/EasePack.min.js" type="text/javascript"></script>
  <script src="js/slider.js" type="text/javascript"></script>
    
</head>

<body>
<div id="topGradient"></div>
<div id='page'>

<?php
  // turn on debugging mode
  error_reporting(E_ALL);
  ini_set('display_errors', True);
  
  // DB connection
  require_once('DBController.phps');
echo "before ";
  $db = new DBController();
    
echo "after ";
echo $db->db()->connect_error ." | ". $db->db()->error;
    
  // includes
  include "updateState.php";
  include "htmlFunctions.php";
  include 'imgResize.php';
  
  // header
  include "header.php";
?>
    
 <div class='content' id='pageContainer'>
 <div id='welcome'><div id='close' class='button small red round'>x</div>
    <h3>Welcome to the video library for inquisitive minds.</h3>
    <p>Here you'll find a selection of mind expanding documentaries and short videos presented in an user friendly resource, free and without ads. Links to trailers and even full documentaries have been added when available.</p>
     
    <p>Are you aware of the root causes of the current <a id='movie' href='inside-job' rel='4'>economic crisis</a>? What the fuss is all about <a id='movie' href='the-elegant-universe' rel='49'>quantum physics</a>? Want to find out about the ins and outs of <a id='movie' href='the-corporation' rel='6'>corporations</a>, the <a id='movie' href='manufactoring-consent' rel='8'>mass media</a>, <a id='movie' href='us-now' rel='36'>politics</a> or the controversial <a id='movie' href='genetic-roulette' rel='68'>GMOs and patenting of life</a>? Have you heard about the potential of <a id='movie' href='global-gardener' rel='48'>permaculture</a> to regenerate depleted landscapes?</p> 
     
    <p>Start on the featured movies below or explore the list of subjects using the menu and sorting options on the left. We strive to update the website regularly so bookmark LucidTree.com and check again for new movies and features. Feel free to leave a comment or send us feedback. Enjoy!</p>
</div>
  
<?php 
    include "featured.php";
    //include "mostRecent.php";
?>
  
<div id='movieContainer'></div>
  
 <div id='sideBar'>
    <div class='sideContainer round glow' id='menu'>
        
    <?php include 'menuCategories.php'; ?>
        
    <div class='menuPadding'></div>
    <?php include "menuView.php"; ?>
        
    <div class='menuPadding'></div>
    <?php include 'menuSorting.php'; ?>
    </div>
     
    <div class='sideContainer round glow' id='bitcoin'><h3>Bitcoin</h3>
    Lucid Tree supports open-source, decentralized crypto-currencies, such as <a href='http://bitcoin.org' target='_blank'>Bitcoin</a>.
    <a href='http://youtu.be/Gc2en3nHxA4' target='_blank'><img id='btcLogo' src='images/BTC.png' id='bitcoin'></a><p id='btcAddress'>1AVT75STJsW77Fuwq5KU4QnJqbBCQddNF9</p>
     </div>
 </div>
  
<div class="docList" id="content">
</div>
  
<?php
     // close db connection as no longer needed
     $db->close(); 
 ?>
<div style='clear:both;'></div>
</div><!--close pageContainer-->
</div><!--close page-->
    
<div class='footer'>
  <ol id="content">
    <li id="about">
        <h1>Disclaimer</h1>
        Some of the movies on this website might challenge your world views and/or atention span. Also, bear in mind that some productions are low budget. So, it can be helpful to be prepared to listen to the message in full (not the messenger or how the message is wrapped up) instead of quickly judging. <br/><br/>Remember, information is power but there's no such thing as unbiased media. Our own discernement, a wide open mind plus a diversity of information sources, are essential in getting ever more close to the truth.
    </li>
    
    <li id="credits">
        <h1>Credits</h1>
        Lucid Tree was hand crafted using the following <a href="http://en.wikipedia.org/wiki/Open_source" target="_blank">open source</a> and/or free technologies:
        <br/><br/>
        <a href='http://www.openkomodo.com' target="_blank">Komodo Edit</a>, 
        <a href='http://filezilla-project.org' target="_blank">FileZilla</a>, 
        <a href='http://www.php.net' target="_blank">PHP</a>, 
        <a href='http://www.mysql.com' target="_blank">MySQL</a>, 
        <a href='http://jquery.com' target="_blank">jQuery</a>, 
        <a href='http://www.asual.com/jquery/address/' target="_blank">jQuery Address</a>, 
        <a href='http://www.greensock.com' target="_blank">GSAP</a>, 
        <a href='http://www.mozilla.org' target="_blank">Firefox</a>,
        <a href='http://getfirebug.com' target="_blank">Firebug</a>,
        <a href='http://www.fontsquirrel.com' target="_blank">Font Squirrel</a> and 
        <a href='http://inkscape.org' target="_blank">Inkscape</a>
        <br/><br/>All video content is hosted on third party websites.
        <br/><br/><br/>
        <h1>Technical note</h1>
        Because this website uses the latest web development technologies, it requires a modern web browser with CSS3/HTML5 support and JavaScript enabled. If you can't see any content you may need to either;
        <a href='http://enable-javascript.com/' target='_blank'>enable JavaScript</a> (it's usually enabled by default) in your browser settings, or upgrade to the latest version of your browser. Alternatively, <a href='http://www.mozilla.org' target='_blank'>get Firefox</a>.
    </li>
    <li id="right">
      <h1>Contact</h1>
      Lucid Tree welcomes constructive feedback, suggestions of features, improvements, movies or quotes, and virtual expressions of appreciation and respect.<br/><br/>
      <div id="emailForm">
        <label for="email">Email: </label><br/>
        <input type="text" name="email" id="email" size="25" value="" /><br/>
        <label for="comments">Message: </label><br/>
        <textarea rows="3" cols="30" type="text" name="feedback" id="feedback"></textarea><br/>
        <a href="#" id="submit" class="submitDisabled">Send</a>
      </div>
    </li>
  </ol>
  
  <div class="clear"></div>
</div>

<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="js/jquery.address-1.5.min.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
<script src="js/views.js" type="text/javascript"></script>

<!-- Piwik -->
<!--<script type="text/javascript">
//var pkBaseURL = (("https:" == document.location.protocol) ? "https://lucidtree.com/piwik/" : "http://lucidtree.com/piwik/");
var pkBaseURL = (("https:" == document.location.protocol) ? "https://localhost/lucidtree.com/piwik/" : "http://localhost/lucidtree.com/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
  var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
  piwikTracker.trackPageView();
  piwikTracker.enableLinkTracking();
} catch( err ) {
  alert("Error: "+err);
};

</script><noscript><p><img src="http://lucidtree.com/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
--><!-- End Piwik Tracking Code -->

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  _paq.push(['setLinkClasses', "a#website"]);
  //  _paq.push(['setLinkTrackingTimer', 1000]);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://lucidtree.com/piwik/";
    //var u=(("https:" == document.location.protocol) ? "https" : "http") + "://localhost/lucidtree.com/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="http://localhost/lucidtree.com/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->

</body>
</html>
