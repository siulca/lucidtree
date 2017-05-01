var DEFAULT_PAGE_TITLE = "Lucid Tree: Movie library";
var MINIPLAYER_HEIGHT = 400;
var MAINPLAYER_HEIGHT = 510;
var DEFAULT_ADDRESS = ["+","all-movies","detailed-view","by-date-added","descending"];
var ADDRESS_ORDER = ["movie","category", "view", "sortBy", "sortDir"];
var prevAddress = [];
var isFirstLoad = true;
// update movie view only if movie has changed
var isMovieChanged = false;

// start listening for address change events
$.address.change(addressChanged);

// control navigation
function addressChanged(event){
    
    // create new featured slider
    var featuredSlider = new Slider(".featuredRow");
    
    // set default param if one not found
    var reload = false;
    for(i=0; i<ADDRESS_ORDER.length; i++){
        var value = $.address.pathNames()[i];
        if(!value){
            event.pathNames[i] = DEFAULT_ADDRESS[i];
            reload = true;
        }
    }
    // reload page if one of the params was not found
    if(reload){
        $.address.path(event.pathNames.join("/"));
        return;
    }
    
    // fetch category content if it changed
    if (event.pathNames[1] != prevAddress[1] || !event.pathNames[1]) {
        // loading message
        $("#content").html("<div class='docRow round'><img src='images/loading.gif'> Loading... </div>");
        
        // default category if undefined
        var params = {data:'contentByCategory'};
        params[ADDRESS_ORDER[1]] = $("#"+ADDRESS_ORDER[1]+"[href="+event.pathNames[1]+"]").attr("rel");
        $.post("getData.php", params, updateView, 'json');
    }else{
        updateView();
    }
}

// set initial event listeners
$(document).ready(init);

function init(){
    //$("a#watchFeature").click(toggleFeaturedPlayer);
    
    $("div#close").click(closePanel);
    
    // menu events
    $(".menuList li").click(updateAddress);
    $(".menuList li").hover(menuOver, menuOut);
    
   // contact form events
    $("a#submit").hide();
    $("a#submit").click(sendMessage);
    $("#email").keypress(validateForm);
    $("#comments").keypress(validateForm);
    
    //TweenMax.to($("#slogan"), 2, {delay:2, css:{autoAlpha:0}, ease:Power2.easeIn, onComplete:showQuote});
    showQuote();
}

function closePanel(event){
    var divToHide = $(this).parent();
    TweenMax.to($(this).parent(), 0.5, {css:{autoAlpha:0, height:1}, ease:Power2.easeOut, onComplete:hideWelcome, onCompleteParams:[divToHide]});
}
function hideWelcome(divToHide){
    divToHide.hide();
}
function menuOver(event){
    TweenMax.to($(this), 0.4, {css:{paddingLeft:"35px", backgroundColor:"#f8f8f8"}, ease:Power3.easeOut});
    TweenMax.to($(this).children("a"), 0.4, {css:{color:"#ffa30b"}, ease:Power3.easeOut});
}

function menuOut(event){
    TweenMax.to($(this), 0.4, {css:{paddingLeft:"30px", marginRight:"0px", backgroundColor:"#fff"}, ease:Power1.easeOut});
    TweenMax.to($(this).children("a"), 1, {css:{color:"#26ADE4"}, ease:Power3.easeIn});
}

function menuSelect(event){
    TweenMax.to($(this), 0.4, {css:{paddingLeft:"25px", marginRight:"20px", backgroundColor:"#f8f8f8"}, ease:Power1.easeOut});
    TweenMax.to($(this).children("a"), 0.5, {css:{color:"#28170b"}, ease:Power3.easeIn});
    $(this).addClass("selectedMenuLink");
}

function menuDeselect(event){
    $(this).removeClass("selectedMenuLink");
    $(this).hover(menuOver, menuOut);
    $(this).mouseleave();
}

function updateAddress(event){
    event.preventDefault();
    
    // prevent mouseleave from being triggered
    $(this).unbind('mouseenter mouseleave');
    
    var newPath = [];
    var link = $(this);
    
    // if menu link
    if($(this).attr("id") != ADDRESS_ORDER[0]){
        link = $(this).children("a");
    }else {
        if(link.attr("href") == prevAddress[0]){
            // force a movie change everytime a movie is clicked
            isMovieChanged = true;
        }
    }
    
    for(i=0;i<ADDRESS_ORDER.length;i++){
        var id = ADDRESS_ORDER[i];
        
        if(link.attr('id') == id){
            newPath.push(link.attr("href"));
            
        }else{
            var pathName = $.address.pathNames()[i];
            
            newPath.push(pathName ? pathName : DEFAULT_ADDRESS[i]);
        }
    }
    
    //console.log(prevAddress.join("/") +" == "+ $.address.pathNames().join("/"));
   
    $.address.autoUpdate(true);
    // force update if movie was clicked but path not changed
    if(isMovieChanged && prevAddress.join("/") == $.address.pathNames().join("/")){
        $.address.update();
    }else{
        // store previous address for future comparisson
        prevAddress = $.address.pathNames();
        $.address.path(newPath.join("/"));
    }
    
    // hack to capture the title after it has changed as it appears to not change imediatelly
    //var i = 0;
    var title = document.title;
    var logDelay = setInterval(function(){
        if(title != document.title){
            clearInterval(logDelay);
            log("pageView");
        }
        //console.log(i++ +" "+ document.title);
    }, 20);
}


function showQuote(){
    //$("#slogan").hide();
    TweenMax.staggerTo([$("#quote"), $("#quoteAuthor")], 4, {css:{autoAlpha:1}, ease:Power2.easeIn}, 2);
    var quotePos = $("#quoteAuthor").position();
    TweenMax.to($(".header"), 1, {css:{height:quotePos.top+40}, ease:Power4.easeInOut});
}

/*
function toggleFeaturedPlayer(event){
    event.preventDefault();
    
    var movieID = $(this).attr("href");
    var player = $(this).parent().parent().parent().children("#player");
    
    // close if open and same button clicked
    if(player.attr("status")=="open" && player.attr("movieID") == movieID)
    {
        closePlayer(player);
    }else{
        //selectButton($(this));
        // deselected any selected button
        $('#featuredList li.selected').not($(this)).each(function() {
            TweenMax.to($(this), 0.5, {css:{className:"-=selected"}});
            //$(this).removeClass("selected");
        });
        
        // close any opened players - except this one
        $('div[status="open"]').not(player).each(function() {
            closePlayer($(this));
            closeComments();
        });
        
        // deselected any selected button
        $('a.isSelected').each(function() {
            deselectButton($(this));
        });
        
        TweenMax.to($(this).parent(), 0.5, {css:{className:"selected"}});
        
        //$(this).parent().addClass("selected");
        openPlayer(player, "watch", movieID);
        
        log("featured", $(this).attr("rel"));
        
        scrollToMovie(player, 0);
    }
}
*/

function togglePlayer(event){
    event.preventDefault();
    
    //var player = $(this).parent().siblings("#miniPlayer");
    var contentType = $(this).attr("id");
    var movieID = $(this).attr("href");
    var player = $(this).parent().parent().children("#player");
    
    var offset = 0;
    // close any opened players - except this one
    $('div[status="open"]').not(player).each(function() {
        // don't close the main player
        //if($(this).attr("id") != "mainPlayer") {
            // store offset if scrolling up
            if($(this).offset().top < player.offset().top) {
                offset = $(this).outerHeight();
            }
            //console.log("offset:"+$(this).offset().top+"|"+player.offset().top);
            closePlayer($(this));
            closeComments();
        //}
    });
    
    // deselected any selected button
    $('a.isSelected').not($(this)).each(function() {
        deselectButton($(this));
    });
    
    // close if open and same button clicked
    if(player.attr("status")=="open" && player.attr("type") == contentType)
    {
        deselectButton($(this));
        closePlayer(player);
    }else{
        selectButton($(this));
        openPlayer(player, contentType, movieID);
        
        scrollToMovie(player, offset);
        
        //
        var viewSize = ($(this).parents('#movieContainer').length) ? "large view" : "small view";
        log(contentType, $(this).attr("rel"), viewSize);
    }
}


// scroll given div into view
function scrollToMovie(movie, offset){
    // new position including marign and offset of already opened player
    var newPos = movie.position().top - offset;
    //alert(">>> "+newPos);
    TweenMax.to($('html, body'), 0.8, {scrollTop:newPos, ease:Power4.easeInOut});
}

function selectButton(btn){
    btn.removeClass("white green");
    btn.addClass("isSelected gray");
    
    var newLabel = "Close "+btn.text().split(" ")[1];
    btn.text(newLabel);
}

function deselectButton(btn){
    btn.removeClass("isSelected gray");
    
    var colourClass = (btn.attr("id") == "trailer") ? "white" : "green";
    btn.addClass(colourClass);
    
    var newLabel = "Watch "+btn.text().split(" ")[1];
    btn.text(newLabel);
}

function openPlayer(player, contentType, movieID){
    player.attr("status", "open");
    player.attr("type", contentType);
    player.attr("movieID", movieID);
    player.html("<img src='images/loading.gif'>");
    
    // fetch content
    $.post("getData.php", {data:"embedCodeByID", type:contentType, id:movieID},
        function(data){
            player.html( data );
        });
    
    var newHeight = $(player).outerWidth()*0.55;
    
    TweenMax.to(player, 1.2, {css:{height:newHeight, padding:20}, ease:Power4.easeInOut});
}

function closePlayer(player){
    player.attr("status", "closed");
    player.attr("type", "");
    player.empty();
    //console.log("closePlayer:"+player);
    
    TweenMax.to(player, 0.8, {css:{height:0, padding:0}, ease:Power4.easeInOut});
    
    // close featured tab if one is open
    $('#featuredList li.selected').not($(this)).each(function() {
        TweenMax.to($(this), 0.5, {css:{className:"-=selected"}});
        //$(this).removeClass("selected");
    });
}

function validateForm(event){
    if($("#email").val().length > 5 && $("#feedback").val().length > 2){
        $("a#submit").show();
    }else{
        $("a#submit").hide();
    }
}

function sendMessage(event){
    event.preventDefault();
    
    // submit form
    var params = {email:$("#email").val(), comments:$("#feedback").val()};
    $.post("emailTheTree.php", params, emailFeedback);
}

function emailFeedback(data){
    $("#emailForm").addClass("sentFeedback");
    $("#emailForm").html( data );
}

function log(action, category, viewSize){
    //var s = action+": "+category;
    
    // track page view
    if(action == "pageView"){
        _paq.push(['setDocumentTitle', document.title]);
        _paq.push(['trackPageView']);
    }else{ // or track an event
        // removed .selected from category
        var selected = false;
        if(category.indexOf("selected") != -1 ){
            selected = true;
            category = category.split(".")[0];
        }
    
        _paq.push(['trackEvent', category, action, viewSize]);
        
        if(action == "watch"){
            _paq.push(['trackGoal', 1, document.title]);
        }else if(action == "trailer"){
            _paq.push(['trackGoal', 2, document.title]);
        }
    }
    
    //if(window.console) console.log(action+", "+category+", "+document.title);
    
    //piwikTracker.trackPageView(s);
}

