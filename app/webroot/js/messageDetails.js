$(document).ready(function() {
    // Correctly set conversationId based on your HTML structure.
    var conversationId = "<?php echo $conversationId; ?>"; // Adjust based on how you're setting this variable in your PHP.

    function loadMessages(page) {
        $.ajax({
            url: '/messageboard/messages/messageDetails/' + conversationId + '?page=' + page,
            type: 'GET',
            success: function(response) {
                $('#messages').append(response);
                $('#showMoreMessages').data('next-page', page + 1);
            }
        });
    }

    $('#showMoreMessages').click(function(e) {
        e.preventDefault();
        var nextPage = $(this).data('next-page');
        loadMessages(nextPage);
    });

    $('#replyForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '/messageboard/messages/reply/' + conversationId,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {

                    $('#messages').prepend('<div class="message"><p>' + response.messageText + '</p><small>Just now</small></div>');
                    $('#replyForm textarea[name="message_text"]').val('');
                   
                    // Optionally clear the form here and/or reload parts of the page
                } else {
                    alert('Reply failed');
                }
            },
            error: function() {
                alert('Reply failed');
            }
        });
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var messageId = $(this).data('message-id');
        $.ajax({
            url: '/messageboard/messages/delete/' + messageId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#message_' + messageId).remove();
                } else {
                    alert('Delete failed');
                }
            }
        });
    });
});
