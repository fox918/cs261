//jQuery
$(function() {
    $.superbox();
    $("#savefloat").hide();

    var toSave = {};
    toSave.material = [];
    toSave.calendar = [];
    toSave.note = [];


    var id = $("input[name*=cr_id]").val();

    function makeDOM(html){
        var wrapper= document.createElement('div');
        wrapper.innerHTML= html;
        return wrapper.firstChild;    
    }

    //edit Handlers:
    //edit Job
    function job_changed(){
        var id = $("input[name=cr_id]").val();
        var resp = $("#cr_resp").val();
        var content = $('#cr_desc').html();
        var request = $.ajax({
            url : "handlers/edit/editJob.php",
            type : "POST",
            data : {cr_id : id, cr_desc : content, cr_resp : resp},
            dataType: "json"
        });
        request.done(function(data){
            var msg=document.createElement("div");
            if(data.errors == 'true'){
                msg.innerHTML=data.errormsgs[0];
                msg.className="warning floating";
            }else{
                msg.innerHTML="Wird gespeichert";
                msg.className="success floating";
            }
            $('#notifications').append(msg);
            window.setTimeout('$("#notifications").empty();',5000);

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
        request.done(function(data){
            var msg=document.createElement("div");
            if(data.errors == 'true'){
                msg.innerHTML=data.errormsgs[0];
                msg.className="warning floating";
            }else{
                msg.innerHTML="Wird gespeichert";
                msg.className="success floating";
            }
            $('#notifications').append(msg);
            window.setTimeout('$("#notifications").empty();',5000);

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
        request.done(function(data){
            var msg=document.createElement("div");
            if(data.errors == 'true'){
                msg.innerHTML=data.errormsgs[0];
                msg.className="warning floating";
            }else{
                msg.innerHTML="Wird gespeichert";
                msg.className="success floating";
            }
            $('#notifications').append(msg);
            window.setTimeout('$("#notifications").empty();',5000);

        });

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
        request.done(function(data){
            var msg=document.createElement("div");
            if(data.errors == 'true'){
                msg.innerHTML=data.errormsgs[0];
                msg.className="warning floating";
            }else{
                msg.innerHTML="Wird gespeichert";
                msg.className="success floating";
            }
            $('#notifications').append(msg);
            window.setTimeout('$("#notifications").empty();',5000);

        });

    }

    //edit file
    $("input[type=\"file\"]").ajaxfileupload({
        'action' : 'handlers/edit/addFile.php',
    'params' : {'cr_id' : id}
    });

    //delete Handlers:
    //delete job
    $("#delete").click(function(){
        var request = $.ajax({
            url : "handlers/edit/delete.php",
            type : "POST",
            data : {cr_id : id},
            dataType: "json"
        });
        request.done(function(data){
            if(data.errors == 'true'){
                console.error("could not delete job");
            }else{
                var msg=document.createElement("div");
                msg.innerHTML="Auftrag gelöscht, Sie werden in Kürze weitergeleitet";
                msg.className="success";
                $('#notifications').append(msg);
                $('#edit').slideUp();
                window.setTimeout("window.location='index.php?page=list';",1000);
            }
        });
    });

    //delete Attachment


    //delete calendar

    //delete Material

    //delete Note

    //new Handlers:
    //add file

    //add Material
    $("#cr_mat_addfield").click(function() {
        console.log("add material");
        var count = $("#cr_mat_counter").val();
        count++;
        var html="<p class='material' id='mat_##NUMBER##'>" +
        "<input style='width:70px' type='text' name='cr_mat_count_##NUMBER##' />" +
        "<input style='left:80px' type='text' name='cr_mat_title_##NUMBER##' />" +
        "<input style='left:270px' type='text' name='cr_mat_note_##NUMBER##' />" +
        "<select style='left:460px' name='cr_mat_state_##NUMBER##'>" +
        "<option>Bestellt</option>" +
        "<option>Geliefert</option>" +
        "<option>Benutzt</option>" +
        "</select>" +
        "<input style='left:560px;width:120px' type='text' name='cr_mat_delivery_##NUMBER##' />" +
        "<input style='left:700px;width:100px' type='text' name='cr_mat_price_##NUMBER##'/>" +
        "<img src='./img/icons/x_alt_16x16.png' onclick='delMat(this);' />" +
        "<input type='hidden' name='cr_mat_submit_##NUMBER##' value='true' />" +
        "</p>";
    materials++;
    html = html.replace(/##NUMBER##/g,""+count);
    var dom=makeDOM(html);
    $("#materials>div").append(dom);

    toSave.material.push(dom);
    $("#savefloat").fadeIn();
    $("#cr_mat_counter").val(count);
    return false;
    });

    //add note
    $("#cr_note_addfield").click(function() {
        console.log("add a note");
        var count = $("#cr_note_counter").val();
        count++;
        var html = "<div class='note' id='note_##NUMBER##'>" +
        "<fieldset>" +
        "<legend>" +
        "<input type='text' name='cr_note_title_##NUMBER##' />" +
        "<img src='./img/icons/x_alt_16x16.png' onclick='delNote(this)' />" +
        "</legend>" +
        "<textarea name='cr_note_##NUMBER##' rows='13' cols='40'></textarea>" +
        "<input type='hidden' name='cr_note_submit_##NUMBER##' value='true' />" +
        "</fieldset></div>";
    notes++;
    html = html.replace(/##NUMBER##/g,""+count);
    $("#cr_note_counter").val(count);
    var dom=makeDOM(html);
    $("#notes>div").append(dom);
    $(dom).find("textarea").tinymce({
        script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
        theme : "simple"
    });
    toSave.note.push(dom);
    $("#savefloat").fadeIn();
    return false;
    });



    //add a date
    $("#cr_date_addfield").click(function() {
        console.log("add a date");
        var count = $("#cr_date_counter").val();
        count++;
        var html ="<fieldset  id='date_##NUMBER##' class='date'>"+
        "                <legend>"+
        "                    Datum <input type='text' name='cr_date_##NUMBER##'/>"+
        "<input type='hidden' name='cr_date_submit_##NUMBER##' value='true' />"+
        "            <img src='./img/icons/x_alt_16x16.png' onclick='remDate(this)' />" +
        "                </legend>"+
        "               <p>"+
        "            <span>"+
        "                <label for='cr_date_statime_##NUMBER##'>Startzeit</label>"+
        "                <input type='text' name='cr_date_statime_##NUMBER##' id='cr_date_statime_##NUMBER##'/>"+
        "            </span>"+
        "            <span>"+
        "                <label for='cr_date_stotime_##NUMBER##'>bis um</label>"+
        "                <input type='text' name='cr_date_stotime_##NUMBER##' id='cr_date_stotime_##NUMBER##'/>"+
        "            </span>"+
        "            <span>"+
        "                <label for='cr_date_desc_##NUMBER##'>Notiz</label>"+
        "                <input type='text' name='cr_date_desc_##NUMBER##' id='cr_date_desc_##NUMBER##'/>"+
        "            </span>"+
        "        </p>"+
        "    </fieldset>";
    html = html.replace(/##NUMBER##/g,""+count);
    var dom=makeDOM(html);
    $("#calendar>div").append(dom);
    toSave.calendar.push(dom);
    $("#savefloat").fadeIn();
    return false;
    });


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


    //delete job
    $("#delete").click(function(e){
    });

    //edit job state
    $("#publish").click(function(){
    });
    $("#billing").click(function(){
    });
    $("#archive").click(function(){
    });


    //save added thingies
    $("#savefloat").click(function(){
        console.log("saving added entries");
        var errors = [];

        //save all materials
        if(toSave.material.length > 0){
            console.log(toSave.material.length+" Materials");
            toSave.material.forEach(function(dom){
                var count = $(dom).find("input[name*=\"cr_mat_count_\"]").val();
                var title = $(dom).find("input[name*=\"cr_mat_title_\"]").val();
                var note = $(dom).find("input[name*=\"cr_mat_note_\"]").val();
                var state = $(dom).find("select[name*=\"cr_mat_state_\"]").val();
                var delivery = $(dom).find("input[name*=\"cr_mat_delivery_\"]").val();
                var price = $(dom).find("input[name*=\"cr_mat_price_\"]").val();
                if($(dom).find("input[name*=\"cr_mat_submit_\"]").val() == 'true'){
                    console.log("submitting date");

                    var request = $.ajax({
                        url : "handlers/edit/addMaterial.php",
                        type : "POST",
                        data : {cr_id:id, cr_mat_count:count, cr_mat_title:title,cr_mat_note:note,
                            cr_mat_state:state, cr_mat_delivery:delivery, cr_mat_price:price},
                        dataType: "json"
                    })
                    request.done(function(data){
                        if(data.errors == 'true'){
                            console.error(data.errormsgs[0]);
                            errors.push(data.errormsgs[0]);
                        }
                    });
                }

            });

        }

        //save all notes
        if(toSave.note.length > 0){
            console.log(toSave.note.length+" notes");
            toSave.note.forEach(function(dom){
                var title = $(dom).find("input[name*=\"cr_note_title_\"]").val();
                var note = $(dom).find("textarea").html();
                if($(dom).find("input[name*=\"cr_note_submit_\"]").val() == 'true'){
                    console.log("submitting date");

                    var request = $.ajax({
                        url : "handlers/edit/addNote.php",
                        type : "POST",
                        data : {cr_id:id, cr_note_title:title, cr_note:note},
                        dataType: "json"
                    })
                    request.done(function(data){
                        if(data.errors == 'true'){
                            console.error(data.errormsgs[0]);
                            errors.push(data.errormsgs[0]);
                        }
                    });
                }
            });
        }

        //save all calendar entries
        if(toSave.calendar.length > 0){
            console.log(toSave.calendar.length+" dates");
            toSave.calendar.forEach(function(dom){
                var date = $(dom).find("input[name*=\"cr_date_\"]").val();
                var statime = $(dom).find("input[name*=\"cr_date_statime_\"]").val();
                var stotime = $(dom).find("input[name*=\"cr_date_stotime_\"]").val();
                var desc = $(dom).find("input[name*=\"cr_date_desc_\"]").val();
                if($(dom).find("input[name*=\"cr_date_submit_\"]").val() == 'true'){
                    console.log("submitting date");
                    var request = $.ajax({
                        url : "handlers/edit/addCalendar.php",
                        type : "POST",
                        data : {cr_id:id, date:date, date_statime:statime, date_stotime:stotime, 
                            date_desc:desc},
                        dataType: "json"
                    });
                    request.done(function(data){
                        if(data.errors == 'true'){
                            console.error(data.errormsgs[0]);
                            errors.push(data.errormsgs[0]);
                        }
                    });
                }
            });
        }


        $('#edit').slideUp();
        $('#savefloat').slideUp();
        errors.forEach(function(item){
            var msg=document.createElement("div");
            msg.innerHTML=item;
            msg.className="error";
            $('#notifications').append(msg);
        });
        var msg=document.createElement("div");
        if(errors.length > 0){
            msg.innerHTML = "Es sind Fehler aufgetreten";
            msg.className = "warning";
        }else{
            msg.innerHTML = "Alle neuen Einträge wurden gespeichert";
            msg.className = "success";
        }
        $("#notifications").append(msg);
        //window.setTimeout("location.reload(true);",1500);
    });


    //prevent submission
    $("form").submit(function(e){
        e.preventDefault();
    });
});
//remove a temporary date

function remDate(obj){
    console.log("remove date");
    $(obj).parent().parent().remove(); 
    $(obj).parent().parent().find("input[name*='cr_date_submit_']").val('false');
}
function remNote(obj){
    console.log("remove note")
        $(obj).parent().parent().remove(); 
    $(obj).parent().parent().find("input[name*='cr_note_submit_']").val('false');
}
function remMat(obj){
    console.log("remove material")
        $(obj).parent().remove(); 
    $(obj).parent().find("input[name*='cr_mat_submit_']").val('false');

}
