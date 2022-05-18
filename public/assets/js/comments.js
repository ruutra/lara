$(document).ready(function() {
    $('#getAllComments').click(function() {
        let userId = window.location.pathname.
            split('/',2).
            toString().
            replace(',', '');
        $.ajax({
            type: 'GET',
            url: '/' + userId + '/all_comments',
            success:function(result){
                $('.comments-wrapper').html(result);
                $('#getAllComments').hide();
            }
        });
    });

    $('.public-access').change(function() {
        let id = $(this).data('book-id');
        $.ajax({
            type: 'GET',
            url:'/public_book/' + id,
            success:function(result){
                if (result) {
                    alert('Теперь книга доступна по ссылке');
                } else {
                    alert('Теперь доступ по ссылке запрещен');
                }
            }
        });
    });
});
