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
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Message sent!');
                    $('#newMessageFormAjax')[0].reset();
                    fetchMessages(1);

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
                } else {
                    alert('Failed to send message.');
                }
            }
        });
    });

    function fetchMessages(page) {
        $.ajax({
            url: '/messageboard/messages/index?page=' + page,
            type: 'GET',
            success: function(response) {
                if (page === 1) {
                    $('.message-list').html(response); // Replace the content if it's the first page
                } else {
                    $('.message-list').append(response);
                }
                $('.show-more-button').data('page', page + 1); // Update the Show More button
            },
            error: function() {
                alert('Messages could not be loaded.');
            }
        });
    }

    $('.show-more-button').on('click', function(e) {
        console.log("show more button clicked");
        e.preventDefault();
        var page = $(this).data('page');
        fetchMessages(page);
    
        $.ajax({
            url: '/messageboard/messages/index?page=' + page,
            type: 'GET',
            success: function(response) {
                console.log(response)
                $('.message-list').append(response); // ボタンのdata-pageを更新
                $('.show-more-button').attr('page', page + 1);
            },
            error:function(xhr, status, error){
                console.error("Error loading more messages:", error);
            }
        });
    });

    // JavaScript for the "Show More" functionality
    $('.show-more-button').on('click', function(e) {
        e.preventDefault();
        var nextPage = $(this).data('next-page'); // Assume this is set correctly

        $.ajax({
            url: '/messages/messageDetails/' + conversationId + '?page=' + nextPage,
            type: 'GET',
            success: function(response) {
                // Append new messages
                $('#conversation').append(response);
                // Update the data-next-page attribute
                $('.show-more-button').data('next-page', nextPage + 1);
            }
        });
    });

});

