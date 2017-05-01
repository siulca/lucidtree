var moviesObj = {};
var maxPageHeight = 4000;
var nextPageMovieIndex = 0;

function updateView(data){
    
    if (data && data.error){
        $("#movieContainer").html( data.error );
        return;
    }
    
    // cache new data if not empty
    if(!$.isEmptyObject(data)) {
        moviesObj = data;
    }
    
    nextPageMovieIndex = 0;
    
    // update movie view only if movie has changed
    //var isMovieChanged = false;
    // update content view only if menu has changed
    var isMenuChanged = false;
    
    // update menu state
    for(i=0; i<ADDRESS_ORDER.length; i++){
        var id = ADDRESS_ORDER[i];
        var value = $.address.pathNames()[i];
        var prevValue = prevAddress[i];
        
        // check if path name has changed
        if(prevValue != value){
            
            if(i == 0){ // movie has changed
                isMovieChanged = true;
                
            }else{ // menu has changed
                // deselect all links in this menu
                $('.menuList a#'+id).parent(".selectedMenuLink").each(menuDeselect);
                
                // revert to default value if invalid pathName
                if(!$('#'+id+'[href="'+value+'"]').length){
                    value = DEFAULT_ADDRESS[i];
                }
                
                // selected link
                $('.menuList a#'+id+'[href="'+value+'"]').parent().each(menuSelect);
                
                // update title for categories
                if(i==1 && !isFirstLoad){
                    hideMovieView();
                }else{
                    $.address.title(DEFAULT_PAGE_TITLE);
                }
                
                isMenuChanged = true;
            }
        }
    }
    
    // update content view only if menu has changed
    if(isMenuChanged){
        
        // sort data by type and direction
        var sortBy = getSelectedByMenu(3);
        var sortDir = (getSelectedByMenu(4) == "ASC");
        sortData(sortBy, sortDir);
        
        // define view's animation
        switch($(".selectedMenuLink").children("#view").attr("rel"))
        {
        case "0": // detailed
            $("#content").empty();
            renderDetailedView();
            break;
        case "1": // list
            renderListView();
            break;
        case "2": // artwork
            renderArtworkView();
            break;
        default:
            // nothing
        }
        
        // content events
        addLinkEvents();
        
        // scroll if not first time
        if(prevAddress.length > 0) scrollToMovie($(".sideContainer"), 0);
    }
    
    //console.log("movie changed: "+isMovieChanged);
    
    // update movie view only if movie has changed
    if(isMovieChanged && $.address.pathNames()[0] != DEFAULT_ADDRESS[0]){
        isMovieChanged = false;
        //var movieID = $("#"+ADDRESS_ORDER[0]+"[href='"+($.address.pathNames()[0])+"']").attr("rel");
        
        var movieSlug = $.address.pathNames()[0];
        
        //console.log("movie has changed:"+movieSlug);
        
        if(movieSlug) {
            // update movie imediately if chached or load it if not
            var movieObj = getMovieBySlug(movieSlug);
            if(movieObj){
                updateMovieView( movieObj );
            }else{
                $.post("getDoc.php", {"slug":movieSlug}, updateMovieView, 'json');
            }
            
        }else{
            hideMovieView();
        }
    }else if($.address.pathNames()[0] == DEFAULT_ADDRESS[0]){
        hideMovieView();
    }
    
    isFirstLoad = false;
}

function hideMovieView(){
    //$.address.title(DEFAULT_PAGE_TITLE);
    $("#movieContainer").hide();
    $("#movieContainer").empty();
    
    // update title
    var id = ADDRESS_ORDER[1];
    var value = $.address.pathNames()[1];
    $.address.title($('.menuList a#'+id+'[href="'+value+'"]').text()+" @ Lucid Tree");
    
    // update path
    var newPath = $.address.pathNames();
    newPath[0] = DEFAULT_ADDRESS[0];
    //console.log("pathnames: "+newPath.join("/"));
    // prevent reload by not updating
    $.address.autoUpdate(false);
    $.address.path(newPath.join("/"));
    $.address.autoUpdate(true);
}

function updateMovieView(movie){
    
    // retrieve single row from JSON if loaded afresh
    if(movie.data) movie = movie.data;
    
    //console.log("updateMovieView:"+JSON.stringify(movie));
    
    if(!movie.title) {
        //console.log("Error in updateMovieView >> can't understand movie objetc: "+JSON.stringify(movie));
        //console.log("hideMovieView4");
        hideMovieView();
        return;
    }
    
    var slug = movie.slug;
    
    $.address.title(movie.title+" @ Lucid Tree");
    
    var html = "<div id='info'>";
    
    var buttonsArr = [];
    buttonsArr.push({label:"Show comments", id:"commentsButton", link:movie.id, slug:slug, colour:"white"});
    if(movie.URLwebsite.length > 0) buttonsArr.push({label:"Visit website", id:"website", link:movie.URLwebsite, slug:slug, colour:"white"});
    if(movie.URLtrailer.length > 0) buttonsArr.push({label:"Watch trailer", id:"trailer", link:movie.id, slug:slug, colour:"white"});
    if(movie.URLwatch.length > 0) buttonsArr.push({label:"Watch movie", id:"watch", link:movie.id, slug:slug, colour:"green"});
    
    var buttons = getHtmlButtons(buttonsArr);
    
    if(movie.cover.length > 0){
        html += "<a id='coverMain' href='"+getImg(movie.cover)+"'><img src='"+getThumb(movie.cover)+"' alt='"+movie.title+" DVD cover'></a>";
    }
    
    html += "<div id='details'><div id='titleRow'>";
    html += "<span id='movie' >"+ movie.title +"</span>";
    html += getHtmlYear(movie.year);
    html += getHtmlTime(movie.totalMins);
    
    if(movie.isFree == "1") html += getHtmlFree();
    if(movie.featured == "1") html += getHtmlFeatured();
    
    html += "</div>";
    html += "<div id='subtitle'>"+ movie.subtitle +"</div>";
    html += "<div id='description'>"+ movie.description + "</div>";
    html += "<div id='categoriesWrapper'>"+getHtmlCategories(movie.category.split(','))+"</div>";
    html += "</div></div>";
    
    html += "<div id='player'></div>";
    html += "<div class='docButtons'>"+ buttons +"</div>";
    html += '<div id="commentsHolder"></div>';
    
    // attach html
    $("#movieContainer").html( html );
    
    // clear selected style
    $("#relatedCategory a").removeAttr("style");
    
    // resize image
    $("#coverMain img").css("width", "183px");
    $("#movieContainer").show();
    
    $("#movieContainer #categories li").click( updateAddress );
    $("#movieContainer #trailer").click( togglePlayer );
    $("#movieContainer #watch").click( togglePlayer );
    $("#movieContainer #commentsButton").click( toggleComments );
    
    // set new window for externl websites
    $("a#website").attr("target", "_blank");
    /*$("a#website").click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        _paq.push(['trackLink', url, 'link']);
    });*/
    
    TweenMax.fromTo($("#movieContainer"), 1, {css:{autoAlpha:0}}, {css:{autoAlpha:1}, ease:Power4.easeInOut});
    
    scrollToMovie($("#movieContainer"), 0);
}

function toggleComments(event){
    event.preventDefault();
    
    if($(this).attr("isOpen")){
        closeComments();
        log("comments-close", $(this).attr("rel"));
    }else{
        openComments();
        log("comments-open", $(this).attr("rel"));
    }
}

function openComments(){
    $("#commentsButton").removeClass("white");
    $("#commentsButton").addClass("gray");
    $("#commentsButton").text("Hide comments");
    $("#commentsButton").attr("isOpen", "true");
    
    var movieID = $("#commentsButton").attr("href");
    var pageTitle = $.address.title();
    $("#commentsHolder").append("<iframe id='commentsFrame' src='comments.php?id="+movieID+"&title="+pageTitle+"'></iframe>");
    
    TweenMax.to($("#commentsFrame"), 1.2, {css:{height:474}, ease:Power4.easeInOut});
    TweenMax.to($("#commentsHolder"), 1.2, {css:{height:500}, ease:Power4.easeInOut});
    
    scrollToMovie($("#commentsHolder"), 80);
}

function closeComments(){
    if($("#commentsButton").length > 0){
        $("#commentsButton").removeClass("gray");
        $("#commentsButton").addClass("white");
        $("#commentsButton").text("Show comments");
        $("#commentsButton").removeAttr("isOpen");
        
        TweenMax.to($("#commentsHolder"), 1.2, {css:{height:8}, ease:Power4.easeInOut});
        
        if($("#commentsFrame").length > 0){
            $("#commentsFrame").remove();
        }
    }
}


function renderDetailedView(){
    // remove loading message if one exists
    $('#loadMore').remove();
    
    var previousHeight = $("#content").height();
    var count = 0;
    
    //$.each(remainingMovies, function() {
    for(var i=nextPageMovieIndex; i<moviesObj.length; i++){
        // get html and append it to content container
        $("#content").append( getHtmlDetailedMovie(moviesObj[i]) );
        
        count++;
        
        // render only one page at a time
        if($("#content").height() - previousHeight > maxPageHeight && moviesObj.length-i > 0){
            // append loading more movies instruction
            $("#content").append( getHtmlLoading() );
            
            // listen for scrolling event
            $(window).scroll(onWindowScroll);
            
            //return false;
            break;
        }
    }//);
    
    //console.log("Displaying from "+nextPageMovieIndex+" "+i+" new movies. Movies left "+moviesObj.length);
    
    // increment the page
    nextPageMovieIndex = i+1;
    
    // resize images
    $("#cover img").css("width", "120px");
    
    addLinkEvents();
    
    // animate the first 8 elements of the newly added set
    var index = $(".docContainer").length - count;
    var animElements = $("div.docContainer").filter(':eq('+index+'), :gt('+index+')');
    TweenMax.staggerFrom(animElements, 0.5, {css:{autoAlpha:0, marginTop:"20px"}, ease:Power4.easeOut}, 0.1);
}

function addLinkEvents(){
    // remove events and add again to prevent duplicate events
    $("a#movie").off("click").click( updateAddress );
    $("a#trailer").off("click").click( togglePlayer );
    $("a#watch").off("click").click( togglePlayer );
}

function onWindowScroll(){
    // stop listening for more pages if view has changed
    if($("div#loadMore").length == 0) {
        $(window).off("scroll");
    }else{
        var loaderOffset =  $(window).scrollTop() + $(window).innerHeight() - $("div#loadMore").offset().top;
        
        // load more content if loading message is 100 pixels away fro the bottom of the viewport
        if(loaderOffset > 0){
            // stop listening to 
            $(window).off("scroll");
            
            //alert("scrolling:"+$(window).innerHeight());
            
            //console.log("LOAD MORE CONTENT!");
            // show the next page
            renderDetailedView();
        }
    }
}

function renderListView(){
    var html = "";
    
    $.each(moviesObj, function() {
        html += "<div id='listRow'>";
        html += getHtmlMovieLink(this.title, this.id, this.slug);
        html += getHtmlYear(this.year);
        html += getHtmlTime(this.totalMins);
        if(this.isFree == "1") html += getHtmlFree();
        if(this.featured == "1") html += getHtmlFeatured();
        html += "</div>";
    });

    $("#content").html( html );

    TweenMax.staggerFrom($("div#listRow"), 0.25, {css:{autoAlpha:0, marginTop:"10px"}, ease:Power1.easeInOut}, 0.02);
}

function renderArtworkView(data){
    var sortBy = getSelectedByMenu(3);
    var html = "<ul id='artwork'>";
    
    $.each(moviesObj, function() {
        var caption = "";
        switch(sortBy){
            case "totalMins":
              caption = this[sortBy] +" mins";
              break;
            case "isFree":
                caption = (this.isFree == "1") ? getHtmlFree() : "";
                break;
            case "createdOn":
                // nothing
                break;
            default:
                caption = this[sortBy]; 
        }
        
        // display image if one exists
        if(this.cover.length > 0){
            html += "<li>";
            html += "<a id='movie' href='"+ this.slug +"' rel='"+this.id+"'><img id='coverLarge' src='"+getThumb(this.cover)+"' alt='"+this.title+" DVD cover'></a>";
            if(caption.length > 0) html += '<div id="caption">'+ caption +'</div>';
            html += "</li>";
        }
    });
    
    $("#content").html( html+"</ul>" );
    $("img#coverLarge").css("width", "183px");
    
    //TweenMax.staggerTo($(".docRow"), 0.5, {width:160}, 0);
    TweenMax.staggerFrom($("#artwork li"), 0.4, {css:{autoAlpha:0, marginTop:"20px"}, ease:Power2.easeOut}, 0.04);
}

function getHtmlButtons(buttons){
    var html = "";
    for(var i=0; i<buttons.length; i++){
        var btn = buttons[i];
        var roundRight = (i == 0) ? "roundBL" : "";
        var roundLeft = (i == buttons.length-1) ? "roundBR" : "";
        html += "<a id='"+btn.id+"' class='button medium "+roundLeft+" "+roundRight+" "+btn.colour+"' href='"+ btn.link +"' rel='"+ btn.slug +"'>"+btn.label+"</a>";
    }
    return html;
}

function getHtmlCategories(categories){
    var html = "<ol id='categories'><li>Topics:</li>";
    
    for(var i=0; i<categories.length; i++) {
        var catLink = $("#category[rel='"+ categories[i] +"']")[0].outerHTML;
        html += "<li id='relatedCategory'> "+catLink+"</li>";
    }
    
    html += "</ol>"

    return html;
}

function getHtmlDetailedMovie(movie){
    var html = "";
    
    var buttons = "";
    if(movie.URLtrailer.length > 0){
      var roundRight = movie.URLwatch.length > 0 ? "" : "roundBR";
      buttons += "<a id='trailer' class='button medium roundBL "+ roundRight +" white' href='"+ movie.id +"' rel='"+ movie.slug +"'>Watch trailer</a>";
    }
    if(movie.URLwatch.length > 0){
      var roundLeft = movie.URLtrailer.length > 0 ? "" : "roundBL";
      buttons += "<a id='watch' class='button medium "+ roundLeft +" roundBR green' href='"+movie.id+"' rel='"+ movie.slug +"'>Watch movie</a>";
    }
    
    html += "<div class='docContainer'><div class='docRow'>";
    
    if(movie.cover.length > 0){
        //html += "<a id='cover' href='"+getImg(this.cover)+"'><img src='"+getThumb(this.cover)+"' alt='"+this.title+" DVD cover'></a>";
        html += "<a id='movie' href='"+ movie.slug +"' rel='"+movie.id+"'><img id='cover' src='"+getThumb(movie.cover)+"' alt='"+movie.title+" thumbnail'></a>";
    }
    
    html += "<div class='docColumn'><div id='titleRow'>";
    html += getHtmlMovieLink(movie.title, movie.id, movie.slug);
    html += getHtmlYear(movie.year);
    html += getHtmlTime(movie.totalMins);
    
    if(movie.isFree == "1") html += getHtmlFree();
    if(movie.featured == "1") html += getHtmlFeatured();
    
    html += "</div>";
    html += "<div id='subtitle'>"+ movie.subtitle +"</div>";
    html += "<div id='description'>"+ movie.description +"</div>";
    html += "</div><div class='clear'></div></div>";
    
    if(buttons.length > 0) {
        html += "<div id='player'></div>";
        html += "<div class='docButtons'>"+ buttons +"</div>";
    }
    
    html += "</div>";
    
    return html;
}

function getHtmlLoading(){
    return "<div id='loadMore' class='roundTop'><img src='images/loading.gif'> Loading more movies...</div>";
}

function getHtmlMovieLink(title, id, slug){
    return "<a id='movie' href='"+ slug +"' rel='"+id+"'>"+ title +"</a>";
}

function getHtmlYear(year){
    return "<div id='year' title='Year of release'>"+ year +"</div>";
}

function getHtmlTime(time){
    return "<div id='mins' title='Running time'>"+ time +" mins</div>";
}

function getHtmlFree(){
    return "<div id='free' title='This movie is copyright free.'>Free</div>";
}

function getHtmlFeatured(){
    return "<div id='featured' title='This movie is highly recommended.'>Featured</div>";
}

function getMovieBySlug(slug) {
  for (var i=0; i<moviesObj.length; i++) {
    if (moviesObj[i].slug === slug) {
      return moviesObj[i];
    }
  }
  return false;
}

function getSelectedByMenu(menuIndex){
    var pathNames = $.address.pathNames();
    return $("#"+ADDRESS_ORDER[menuIndex]+"[href="+pathNames[menuIndex]+"]").attr("rel");
}

function sortData(prop, asc) {
    moviesObj = moviesObj.sort(function(a, b) {
        if (asc) return (a[prop] > b[prop]);
        else return (b[prop] > a[prop]);
    });
}

/*
function textToSlug(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
    
    // remove accents, swap ñ for n, etc
    var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    var to   = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
      str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }
    
    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
      .replace(/\s+/g, '-') // collapse whitespace and replace by -
      .replace(/-+/g, '-'); // collapse dashes
    
    return str;
};
*/

function getThumb(img){
    return "images/covers/thumbs/"+img;
}

function getImg(img){
    return "images/covers/"+img;
}