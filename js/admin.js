$(function(){

    //reset a password
            $("#list ul li p button").click(function(){
                var userid = $(this).val();
                var request = $.ajax({
                    url : "handlers/resetPass.php",
                    type : "POST",
                    data : {uid:userid},
                    dataType: "json"
                });
                request.done(function(data){
                    $("#notifications").empty();
                    if(data.errors == 'false'){
                        var msg=document.createElement("div");
                        msg.innerHTML="Das neue Passwort lautet: "+data.new_pass;
                        msg.className="success";
                        $('#notifications').append(msg);

                    }else{
                        var msg=document.createElement("div");
                        msg.innerHTML=data.errormsgs[0];
                        msg.className="error";
                        $('#notifications').append(msg);
                    }
                });

            });

    //create a user
    $("#newUser button").click(function(){
        var name = $("#newUser input[name=new_name]").val();
        var pw = $("#newUser input[name=new_pw]").val();
        var type = $("#newUser select[name=new_type]").val();
        var request = $.ajax({
            url : "handlers/createUser.php",
            type : "POST",
            data : {new_name : name, new_pw : pw, new_type : type},
            dataType: "json"
        });
        request.done(function(data){
            $("#notifications").empty();
            if(data.errors == 'false'){
                var msg=document.createElement("div");
                msg.innerHTML="Benutzer "+name+" wurde erstellt";
                msg.className="success";
                $('#notifications').append(msg);
                $("#admin").slideUp();
                setTimeout('location.reload();',1000);

            }else{
                var msg=document.createElement("div");
                msg.innerHTML=data.errormsgs[0];
                msg.className="error";
                $('#notifications').append(msg);
            }
        });
    });
    
    //change pw
    $("#change_pw").click(function(){
        var newpw = $("#pw").val();
        var request = $.ajax({
            url : "handlers/changePass.php",
            type : "POST",
            data : {pw : newpw},
            dataType: "json"
        });
        request.done(function(data){
            $("#notifications").empty();
            if(data.errors == 'false'){
                var msg=document.createElement("div");
                msg.innerHTML="Password wurde geändert";
                msg.className="success";
                $('#notifications').append(msg);
            }else{
                var msg=document.createElement("div");
                msg.innerHTML=data.errormsgs[0];
                msg.className="error";
                $('#notifications').append(msg);
            }
        });


    }); 

});
