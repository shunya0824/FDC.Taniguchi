$(document).ready(function() {
    $('.new-message-btn').click(function() {
        $('#newMessageForm').toggle();
    });

    $('#recipientUsername').autocomplete({
        source: '/messageboard/users/autocomplete',
        minLength: 2,
        select: function(event, ui) {
            $('#recipientUsername').val(ui.item.label);
            $('#recipientId').val(ui.item.value);
            return false;
        }
    });

    $('#newMessageFormAjax').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data)
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Message sent!');
                    $('#newMessageFormAjax')[0].reset();

                    var newMessageHtml = '<div class="message sent">' +
                        '<div class="message-details">' +
                        '<p>To: ' + response.recipientName + '</p>' +
                        '<div class="message-content">' +
                        '<p>' + response.messageText + '</p>' +
                        '<small>Just now</small>' +
                        '</div>' +
                        '<a href="#" class="delete-message delete-link">Delete</a>' +
                        '</div>' +
                        '</div>';
                    $('.message-list').prepend(newMessageHtml); 
                } else {
                    alert('Failed to send message.');
                }
            }
        });
    });

    $('.show-more-button').on('click', function(e) {
        e.preventDefault();
        var page = $(this).data('page'); // 次のページ番号を取得

        $.ajax({
            url: '/messages/index?page=' + page,
            type: 'GET',
            success: function(response) {
                // 成功時にメッセージリストに追加
                $('.message-list').append(response);

                // 必要に応じて"Show More"ボタンの更新または削除を行う
            },
            error: function() {
                alert('Messages could not be loaded.');
            }
        });
    });
});

