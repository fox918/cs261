//jQuery
$(function() {
    $.superbox();

    $("textarea").tinymce({
        script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
        theme : "simple"
    });


    //close button animation
    $('.buttoncontainer').hover(
        function(){
            $(this).find('.closebutton').show();
        },
        function(evt){
            $(this).find('.closebutton').hide();
        });

    //change note

    //add material

    //add note

    //add date

    //add file



    //delete material


    //delete notes


    //delete date


    //delete file


    //reload site

});
