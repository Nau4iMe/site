$(document).ready(function() {
    $('.global_error').click(function() {
        $(this).remove();
    });

    $('.global_success').click(function() {
        $(this).remove();
    });

    $('.btn-danger').click(function() {
        return confirm('ВНИМАНИЕ! Потвърждавате ли изтриването?');
    });

    $(document).on('click', '.video-destroy-ajax', function(event) {
        event.preventDefault();
        var element = $(this);

        $.ajax({
            method: 'DELETE',
            url: element.attr('action')
        }).success(function(data) {
            element.closest('tr').html('<td colspan="3">'+data+'</td>');
        }).fail(function(error) {
            alert(error);
        });

    });

});

function getVideos(url, content_id, token, callback) {
    $.ajax({
        type: 'POST',
        url: url,
        data: { content_id: content_id, _token: token }
    }).success(function(data) {
        callback(data);
    }).fail(function(error) {
        console.log(error);
    });
}
