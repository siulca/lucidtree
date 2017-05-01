$(function(){
    var i = 0;
    var isChecking = true;
    var links = $("input:hidden");
    
    $("#ckeckLinksBtn").click(function(){
        if(i == 0){
            isChecking = true;
            $(this).html("Checking... click to stop");
            checkLink();
        }else{
            isChecking = false;
            $(this).html("Check links");
            i = 0;
        }
    });

    function checkLink(){
        var el = $(links[i]);
        var vid = el.val();

        //console.log("--------------------------");
        console.log(i +" "+ vid);

        if(vid.length > 0){
            var host = getVideoType(vid);

            if(host == "youtube" || host == "vimeo"){

                if(host == "vimeo"){
                    el.parent().append("V");
                }else if(host == "youtube"){
                    el.parent().append("You");
                }

                //
                checkVideoExists(vid, function(exists){
                    if(exists){
                        el.parent().append("+");
                    }else{
                        el.parent().append("-");
                        el.parent().css("background-color", "#000");
                        el.parent().css("background-image", "none");
                    }

                    checkNext();
                });

                return;
            }else {
                el.parent().append("?");
                el.parent().addClass("orange");
                el.parent().removeClass("green");
            }
        }else{
            console.log(i+ "oops, no link!");
        }

        checkNext();
    }

    function checkNext(){
        // check next video if it exists
        i++;
        if($(links[i]).length > 0 && isChecking) setTimeout(checkLink, 100);
    }

    function getVideoType(vid){
        if(vid.indexOf("youtube.com") > -1){
            return "youtube";
        }else if(vid.indexOf("vimeo.com") > -1){
            return "vimeo";
        }else{
            return "???";
        }
    }

    function checkVideoExists (data, callback) {
        data.match(/\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be))\/(video\/|embed\/|watch\?v=)?([A-Za-z0-9._%-]*)(\&\S+)?/);

        var match = {
            provider: null,
            url: RegExp.$2,
            id: RegExp.$5
        }

        //console.log(match);

        if(match.url == 'youtube.com' || match.url == 'youtu.be'){
            /*var request = $.ajax({
                url: 'http://gdata.youtube.com/feeds/api/videos/'+ match.id,
                timeout: 5000,
                success: function(){
                    match.provider = 'YouTube';
                }
            });*/
            var request = $.get('https://gdata.youtube.com/feeds/api/videos/' + match.id + '?v=2&alt=jsonc&callback=?', function(data) {
                //console.log(data);
                callback(data.data ? true : false);
            }, 'jsonp');
        }else if (match.url == 'vimeo.com'){
            var request = $.ajax({
                url: 'http://vimeo.com/api/oembed.xml?url=http%3A//vimeo.com/'+match.id,
                //url: 'http://www.vimeo.com/video/'+match.id,
                timeout: 3000,
                //dataType: 'json',
                success: function (data){
                    //console.log("YAYYYY");
                    //console.log(JSON.parse(data).status);
                    callback(true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(textStatus +" "+ errorThrown);
                    callback(false); //or whatever
                }
            });

        }
    }

});
