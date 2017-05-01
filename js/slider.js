function Slider(contentElement){
    var mask = $(contentElement + " .sliderMask");
    var content = $(contentElement + " .sliderContent");
    var previousBtn = $(contentElement + " .sliderPrevious");
    var nextBtn = $(contentElement + " .sliderNext");
    var visibleWidth = mask.width()+32;
    var scrolableWidth = content.width()-visibleWidth;
    var contentHeight = content.outerHeight();
    var page = 0;
    var totalPages = Math.ceil(scrolableWidth/visibleWidth);
    
    updateState();
    
    // adjust mask and button height to match content
    mask.height(contentHeight);
    previousBtn.add(nextBtn).height(mask.outerHeight()+20).css("line-height", (mask.outerHeight()+20) +"px");
    
    
    // functions 
    function slidePrevious(e){
        slide(-1);
    }
    
    function slideLeft(e){
        slide(1);
    }
    
    function slide(direction){
        var newPage = page + direction;
        
        // keep content within boundaries
        if(newPage >= 0 && newPage <= totalPages){
            
            page = newPage;
            var newX = -visibleWidth * page;

            if(Math.abs(newX) > scrolableWidth) {
                newX = -scrolableWidth;
            }else if(newX > 0){
                newX = 0;
            }
            
            TweenMax.to(content, 1.2, {left:newX, ease:Quad.easeInOut});
            
            log("featured", "page "+ (page+1));
            
            updateState();
        }
    }
    
    function updateState(){
        if(page == 0){
            disableBtn(previousBtn);
            previousBtn.unbind("click");
        }else if(!previousBtn.data("events")){
            enableBtn(previousBtn);
            previousBtn.click(slidePrevious);
        }
        
        if(page == totalPages){
            nextBtn.unbind("click");
            disableBtn(nextBtn);
        }else if(!nextBtn.data("events")){
            enableBtn(nextBtn);
            nextBtn.click(slideLeft);
        }
        
        $(contentElement+" .pageNum").html((page+1) +"/"+ (totalPages+1));
    }
    
    function disableBtn(btn){
        TweenMax.to(btn, 0.4, {className:"+=btnDisabled", ease:Back.easeIn});
    }
    
    function enableBtn(btn){
        TweenMax.to(btn, 0.4, {className:"-=btnDisabled", ease:Back.easeOut});
    }
    
}