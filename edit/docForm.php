<?php

if(isset($doc['id'])) {
    $docID = $doc['id'];
}else if(isset($_GET['id'])){
    $docID = $_GET["id"];
}

?>
<div id='feedback'></div>
<form method="post" id="docForm" enctype="multipart/form-data" action="">
<input type="hidden" name="id" value="<?php echo $docID ?>">
    <fieldset id='edit'>
        <legend><?php echo $pageTitle ?></legend>
            <br/>
            
            <table cellpadding="2" id="edit" border='0'>
                <tr>
                    <td rowspan="11" width="340"><label for="cover" class="innerLabel">Cover image </label>
                    <input type="text" name="cover" id="cover" size="45" value="<?php if(isset($doc['cover'])) echo $doc['cover']; ?>" /><br/>
                    
                    <div id='imageUI'>
                        <!--<input  class="button blue small" name="uploadImage" type="button" value="Upload image">-->
                        
                        <?php
                            if(isset($imgRatio) && $imgRatio > 0){
                                echo "<a href='".$imgURL."' tahrget='_blank'><img src='". $imgURL ."' ". $imgSizeSmall ." ></a>";
                                echo "<br/><b>Dimensions</b> ". $imgSize[0] ." x ". $imgSize[1] ;
                                echo "<br/><b>Size</b> ". $imgKb ." kB";
                                echo "<br/><b>Scale</b> ". $imgRatio*100 . "%";
                                echo "<br/><b>Thumbnail exists?</b> ". ($thumbExists ? "Yes ~ ".$thumbKb." kB" : "NO!!!") ;
                                //echo "<br><img src='". $imgThumb ."' width='25%' height='25%'>";
                            }
                        ?>
                        <br/><br/><input type="file" size="30" name="file">
                    </div>
                    </td>
                
                    <td class="labelCell"></td>
                    <td colspan="2">uid: <?php echo $docID ?>
                        <?php
                            $visible = (!empty($doc['visible']) && $doc['visible']==1) ? "checked='checked'" : "";
                            echo "<span class='right white'> <input type='checkbox' id='visible' name='visible' value='visible' ". $visible ."/>";
                        ?>
                        <label for='visible'>is visible</label> </span>
                    </td>
                </tr>
                 <tr>
                    <td class="labelCell"></td>
                    <td colspan="2">created on: <?php if(isset($doc['createdOn'])) echo $doc['createdOn']; ?></td>
                </tr>
                 <tr>
                    <td class="labelCell"></td>
                    <td colspan="2">url: <b><?php if(isset($doc['slug'])) echo $doc['slug']; ?></b></td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="title" class="innerLabel">Title </label></td>
                    <td colspan="2"><textarea rows="1" cols="75" type="text" name="title" id="title"><?php if(isset($doc['title'])) echo $doc['title']; ?></textarea></td>
                    
                    <td rowspan="9">
                        <?php
                            while($row = $categories->fetch_array()){
                                $checked = (!empty($doc['category']) && in_array($row['id'], explode(",", $doc['category'])) !== FALSE) ? "checked='checked'" : "";
                                echo "<div class='category'><input type='checkbox' id='category".$row['id']."' name='category[]". $row['id'] ."' value='". $row['id'] ."' ". $checked ."/>";
                                echo "<label for='category".$row['id'] ."'>". $row['category'] ."</label></div>";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="subtitle" class="innerLabel">Subtitle </label></td>
                    <td colspan="2"><textarea rows="1" cols="75" type="text" name="subtitle" id="subtitle"><?php if(isset($doc['subtitle'])) echo $doc['subtitle']; ?></textarea></td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="year" class="innerLabel">Year </label></td>
                    <td><input type="text" name="year" id="year" size="8" value="<?php if(isset($doc['year'])) echo $doc['year']; ?>" /></td>
                    <td>
                        <?php $checked = (!empty($doc['isFree']) && $doc['isFree']==1) ? "checked='checked'" : "";
                        echo "<input type='checkbox' id='free' name='free' value='free' ". $checked ."/>";?>
                        <label for='free'>is free</label>
                    </td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="mins" class="innerLabel">Minutes </label></td>
                    <td><input type="text" name="mins" id="mins" size="8" value="<?php if(isset($doc['totalMins'])) echo $doc['totalMins']; ?>" /></td>
                    <td border="1">
                        <?php $checked = (!empty($doc['featured']) && $doc['featured']==1) ? "checked='checked'" : "";
                        echo "<input type='checkbox' id='featured' name='featured' value='featured' ". $checked ."/>";?>
                        <label for='free'>is featured</label>
                    </td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="desc" class="innerLabel">Description </label></td>
                    <td colspan="2"><textarea rows="12" cols="75" type="text" name="desc" id="desc"><?php if(isset($doc['description'])) echo $doc['description']; ?></textarea></td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="official" class="innerLabel">Site link </label></td>
                    <td colspan="2"><textarea rows="1" cols="75" type="text" name="official" id="official"><?php if(isset($doc['URLwebsite'])) echo $doc['URLwebsite']; ?></textarea></td>
                </tr>
                <tr>
                    <td class="labelCell"><label for="trailer" class="innerLabel">Trailer </label></td>
                    <td colspan="2"><textarea rows="2" cols="75" type="text" name="trailer" id="trailer"><?php if(isset($doc['URLtrailer'])) echo $doc['URLtrailer']; ?></textarea></td>
                    </tr>
                <tr>
                    <td class="labelCell"><label for="watch" class="innerLabel">Movie </label></td>
                    <td colspan="2"><textarea rows="2" cols="75" type="text" name="watch" id="watch"><?php if(isset($doc['URLwatch'])) echo $doc['URLwatch']; ?></textarea></td>
                </tr>
            </table>
           
    </fieldset>
    <input class="button green right" type="submit" name="submit" value="Save" />
</form>

<script src="../js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="../js/jquery.jlabel-1.3.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(init);
    function init(){
        $(':text').jLabel();
        $('textarea').jLabel();
        
        $('.innerLabel').css("color", "#2200cc");
        
        if($("#id").attr("value") == "new"){
            $('#imageUI').hide();
        }
        
        //$(":uploadImage").click(uploadImage);
        $("#docForm").submit( sendForm );
    }
    
    function uploadImage(e){
        //$.post("uploadImage.php", params, emailFeedback);
    }
    
    function sendForm(e) {
        e.preventDefault();
        
        var formData = new FormData($("#docForm")[0]);
        
        //$.post('updateDoc.php', $(this).serialize(), sendFormFeedback, 'json');
        //console.log("fomrdata: "+$("#docForm").serialize());
        $.ajax({
            url: 'updateDoc.php',  //server script to process data
            type: 'POST',
            /*xhr: function() {  // custom xhr
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // for handling the progress of the upload
                }
                return myXhr;
            },*/
            
            //Ajax events
            success: sendFormFeedback, error: errorHandler,
            
            // Form data
            data: formData, dataType: 'json',
            
            //Options to tell JQuery not to process data or worry about content-type
            cache: false, contentType: false, processData: false
        });
        
        return false; 
    }
    
    function errorHandler(xhr, textStatus, error){
        console.log("error sending form:"+textStatus+" | "+error);
    }
    
    function sendFormFeedback(obj){
        console.log("success sending form:"+obj);
        
        if(obj.id) {
            $(location).attr('href',"editDoc.php?id="+obj.id);
        }
        if(obj.message) {
            $("#feedback").html(obj.message);
            TweenMax.to($("#feedback"), 0.1, {css:{autoAlpha:1}, ease:Power1.easeInOut});
            TweenMax.to($("#feedback"), 2, {delay:8, css:{autoAlpha:0}, ease:Power1.easeInOut});
        }
    }
</script>
