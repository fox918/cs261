$(function(){

    $(".job").click(function(){
        var orderID=$(this).attr("id");
        window.location ="./index.php?page=edit&order="+orderID;
    });

    $("select[name*=\"sortby\"]").change(function(){
        console.log("sorting");
        $(this).parent().parent().submit();
    });   


});
