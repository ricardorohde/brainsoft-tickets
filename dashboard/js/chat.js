$(document).ready(function(){
    $("#chats-in-reception").load("refresh.php");
    refresh();
});

function refresh() {
    setTimeout(function(){
        $(".chat-code").on('click', function(){
            var chat = $(this).html();
            var source = "https://brainsoft.meupct.com/_chat/atendente.php?int-brainsoft/" + chat;
            $("#talk-in-chat").attr('src', source);
        });
    }, 3000);

    setTimeout(function(){
        $("#chats-in-reception").load("refresh.php");
        refresh();
    }, 7000);
}