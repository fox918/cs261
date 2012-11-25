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

    $("#save").click(function(){
        $('#action_input').val('save');
        });
    

    $("#delete").click(function(e){
        $('#action_input').val('delete');
        });
    $("#publish").click(function(){
        $('#action_input').val('publish');
        });
    $("#billing").click(function(){
        $('#action_input').val('billing');
        });
    $("#archive").click(function(){
        $('#action_input').val('archive');
        });

    //change update
    $("form").submit(function(e){
        e.preventDefault();

        dataString = $("form").serialize();

        $.ajax({
            type: "POST",
            url: "./handlers/editHandler.php",
            data: dataString,
            dataType: "json",
            success: function(data) {
                if(data.errors == false){
                    var msg=document.createElement("div");
                    msg.innerHTML="Auftrag erfolgreich erstellt, Sie werden in Kürze weitergeleitet";
                    msg.className="success";
                    $('#notifications').append(msg);
                    $('#edit').slideUp();
                    window.setTimeout("window.location='index.php?page=list';",1000);

                } else {
                    data.errormsgs.forEach(
                        function(item){
                            var msg=document.createElement("div");
                            msg.innerHTML=item;
                            msg.className="error";
                            $('#notifications').append(msg);
                            window.scrollTo(0,0);
                        });
                }
            }
        });         

    });
});
