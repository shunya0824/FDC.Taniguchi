$(document).ready(function() {
    var conversationId = $('#conversationId').val(); // Assume an input holding the conversation ID
    
    // Function to load messages for the conversation
    function loadMessages(page) {
        $.ajax({
            url: `/messages/messageDetails/${conversationId}?page=${page}`,
            type: 'GET',
            success: function(response) {
                if (page === 1) {
                    $('#messagesContainer').html(response);
                } else {
                    $('#messagesContainer').append(response);
                }
                // Update pagination data
                var nextPage = parseInt(page, 10) + 1;
                $('#showMoreBtn').data('page', nextPage);
            },
            error: function() {
                alert('Failed to load messages.');
            }
        });
    }

    // Initial load of messages
    loadMessages(1);

    // Reply to the conversation
    $('#replyForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: `/messages/reply/${conversationId}`,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    
                    $('#replyForm textarea[name="message"]').val(''); 
                    loadMessages(1); // Reload messages to include the new one
                } else {
                    alert('Failed to send message.');
                }
            },
            error: function() {
                alert('Error replying to conversation.');
            }
        });
    });

    // Show more messages (pagination)
    $('#showMoreBtn').click(function(e) {
        e.preventDefault();
        var page = $(this).data('page') || 2;
        loadMessages(page);
    });

    // Delete a message
    $(document).on('click', '.deleteMessageBtn', function(e) {
        e.preventDefault();
        var messageId = $(this).data('message-id');
        if (!messageId) return;

        if(confirm('Are you sure you want to delete this message?')) {
            $.ajax({
                url: `/messages/delete/${messageId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Remove the message from the DOM.
                        $(`#message_${messageId}`).fadeOut('slow', function() { $(this).remove(); });
                    } else {
                        alert('Failed to delete message.');
                    }
                },
                error: function() {
                    alert('Error deleting message.');
                }
            });
        }
    });
});
