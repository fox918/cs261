//jQuery
$(function() {
    $("textarea").tinymce({
        script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
    theme : "simple"
    });
    function makeDOM(html){
        var wrapper= document.createElement('div');
        wrapper.innerHTML= html;
        return wrapper.firstChild;    
    }

    //counters
    var materials=1;
    var notes=1;
    var dates=1;
    var files=1;

    //update all counter fields
    function updateCounters(){
        $('#cr_mat_counter').val(materials);
        $('#cr_note_counter').val(notes);
        $('#cr_date_counter').val(dates);
        $('#crt_file_counter').val(files);

    }
    //add more material
    $("#cr_mat_addfield").click(function() {
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
        "<img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().remove()' />" +
        "</p>";
    materials++;
    html = html.replace(/##NUMBER##/g,""+materials);
    var dom=makeDOM(html);
    $("#materials>div").append(dom);
    updateCounters();
    return false;
    });

    //add more notes
    $("#cr_note_addfield").click(function() {
        var html = "<div class='note' id='note_##NUMBER##'>" +
        "<fieldset>" +
        "<legend>" +
        "<input type='text' name='cr_note_title_##NUMBER##' />" +
        "<img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().parent().remove()' />" +
        "</legend>" +
        "<textarea name='cr_note_##NUMBER##' rows='13' cols='40'></textarea>" +
        "</fieldset></div>";
    notes++;
    html = html.replace(/##NUMBER##/g,""+notes);
    var dom=makeDOM(html);
    $("#notes>div").append(dom);
    $(dom).find("textarea").tinymce({
        script_url : '../js/tinymce/jscripts/tiny_mce/tiny_mce.js',
        theme : "simple"
    });
    updateCounters();
    return false;
    });

    //add more appointments
    $("#cr_date_addfield").click(function() {
        var html ="<fieldset  id='date_##NUMBER##' class='date'>"+
        "                <legend>"+
        "                    Datum <input type='text' name='cr_date_##NUMBER##'/>"+
        "            <img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().parent().remove()' />" +
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
    dates++;
    html = html.replace(/##NUMBER##/g,""+dates);
    var dom=makeDOM(html);
    $("#calendar>div").append(dom);
    updateCounters();
    return false;
    });

    //add more files
    $("#cr_file_addfield").click(function() {
        var html = "<p>"+
        "    <label for='cr_file_##NUMBER##'>Datei hochladen: </label>       "+
        "    <input type='file' name='cr_file_##NUMBER##' style='width:400px'/>"+
        "    <img src='./img/icons/x_alt_16x16.png' onclick='$(this).parent().remove()' />" +
        "    </p>";
    files++;
    html = html.replace(/##NUMBER##/g,""+files);
    var dom=makeDOM(html);
    $("#files>div").append(dom);
    updateCounters();
    return false;
    });

    //submit functionality

    $("#createForm").submit(function(e){
        e.preventDefault();

        dataString = $("#createForm").serialize();

        $.ajax({
            type: "POST",
            url: "./handlers/createHandler.php",
            data: dataString,
            dataType: "json",
            success: function(data) {
                if(data.errors == false){
                    var msg=document.createElement("div");
                    msg.innerHTML="Auftrag erfolgreich erstellt, Sie werden in Kürze weitergeleitet";
                    msg.className="success";
                    $('#notifications').append(msg);
                    $('#create').slideUp();
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

