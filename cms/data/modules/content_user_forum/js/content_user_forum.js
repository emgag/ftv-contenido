$(document).ready(function() {

$(".like, .dislike").click(function(e){
    var dir = $(this).children("a").attr("href");
    window.location=dir;
});

$("#calendarTable td").each(function(){
    if($(this).css("paddingLeft").replace("px", "") >= "200"){
       $(this).parent().find(".reply, .reply_quote").remove(); 
    }
});


if($(".form_status_message").children().length == "0"){
    $(".form_status_message").remove();
}

});