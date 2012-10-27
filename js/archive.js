$(function(){

$(".job").click(function(){
    var orderID=$(this).attr("id");
    window.location ="./index.php?page=edit&order="+orderID;
});
    
    
    
    
});
