//jQuery
$(function() {
    $.superbox();

    //edit Handlers:
    //edit Job
    function job_changed(){
        var id = $("input[name*=cr_id]").val();
        var resp = $("#cr_resp").val();
        var content = $('#cr_desc').html();
        var request = $.ajax({
            url : "handlers/edit/editJob.php",
            type : "POST",
            data : {cr_id : id, cr_desc : content, cr_resp : resp},
            dataType: "json"
        });
    }

    $("#cr_resp").change(function(obj){
        job_changed();
    });

    function textareaEdited(inst){
        //alert(inst.getBody().innerHTML);
        var type=inst.getElement().getAttribute("id");

        if(type == 'cr_desc'){
            job_changed();
        }else{
            note_changed(inst, type); 
        }
    }

    //edit calendar
    $(".date").change(function(obj){
        console.log("date changed");

        var id = $(this).children("input[name*=cr_date_id_]").val();
        var statime = $(this).find("input[name*=cr_date_statime_]").val();
        var date = statime;
        var stotime =  $(this).find("p input[name*=cr_date_stotime_]").val(); 
        var desc =  $(this).find("p input[name*=cr_date_desc_]").val();

        var request = $.ajax({
            url : "handlers/edit/editCalendar.php",
            type : "POST",
            data : {cr_date_id:id, date_statime:statime, date_stotime:stotime, date:date, date_desc:desc},
            dataType: "json"
        });

    });


    //edit material
    $(".material").change(function(){
        console.log("material changed");
        var id =   $(this).children("input[name*=cr_mat_id_]").val();
        var count = $(this).children("input[name*=cr_mat_count_]").val();
        var title = $(this).children("input[name*=cr_mat_title_]").val();
        var note = $(this).children("input[name*=cr_mat_note_]").val();
        var state = $(this).children("select[name*=cr_mat_state_]").val();
        var delivery = $(this).children("input[name*=cr_mat_delivery_]").val();
        var price = $(this).children("input[name*=cr_mat_price_]").val();

        var request = $.ajax({
            url : "handlers/edit/editMaterial.php",
            type : "POST",
            data : {cr_mat_id:id, cr_mat_count:count, cr_mat_title:title,cr_mat_note:note,
                cr_mat_state:state, cr_mat_delivery:delivery, cr_mat_price:price},
            dataType: "json"
        })
    });

    //edit note
    function note_changed(tinymce, type){
        regex = /.*(\d+)/;
        regex.exec(type);
        type = 'input[name = "cr_note_id_'+RegExp.$1+'"]'; 
        var id = $(type).val();
        var content = tinymce.getBody().innerHTML;

        var request = $.ajax({
            url : "handlers/edit/editNote.php",
            type : "POST",
            data : {cr_note_id : id, cr_note:content},
            dataType: "json"
        })

    }

    //edit file


    //delete Handlers:
    //delete job

    //delete Attachment

    //delete calendar

    //delete Material

    //delete Note

    //new Handlers:
    //add file

    //add Material

    //add note

    //add calendar

    $("textarea").tinymce({
        script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
    theme : "simple",
    onchange_callback : textareaEdited
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
    
    //delete job
    $("#delete").click(function(e){
        $('#action_input').val('delete');
    });
    
    //edit job state
    $("#publish").click(function(){
        $('#action_input').val('publish');
    });
    $("#billing").click(function(){
        $('#action_input').val('billing');
    });
    $("#archive").click(function(){
        $('#action_input').val('archive');
    });

    //prevent submission
    $("form").submit(function(e){
        e.preventDefault();
    });
});
