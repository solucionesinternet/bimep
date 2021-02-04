$( document ).ready(function() {
    // Handler for .ready() called.

    if($('#user_particular, .user_particular').attr('checked', true)){
        $('.group_company').css("display", "none");
    }else {
        $('.group_company').css("display", "block");
    }

    $('#user_particular, .user_particular').click(function() {
        $( ".group_company" ).toggle();
    });
});