$(document).ready(function() {
    $('#getAllComments').click(function() {
        let userId = window.location.pathname.substring(1);
        $.ajax({
            type: 'GET',
            url:'/' + userId + '/all_comments',
            success:function(result){
                $('.comments-wrapper').html(result);
                $('#getAllComments').hide();
            }
        });
    });
});
