<?php
// /View/Elements/messages_list.ctp

foreach ($messages as $message) {
    // Assuming $message['Message'] and related models like 'Sender' and 'Recipient' are available
    echo '<div class="message ' . ($message['Message']['sender_id'] == $currentUserId ? 'sent' : 'received') . '">';
    if ($message['Message']['sender_id'] == $currentUserId) {
        echo '<p>To: ' . h($message['Recipient']['username']) . '</p>';
    } else {
        echo '<p>From: ' . h($message['Sender']['username']) . '</p>';
    }
    echo '<p>' . h($message['Message']['message_text']) . '</p>';
    echo '<small>' . h($message['Message']['created']) . '</small>';
    if ($message['Message']['sender_id'] == $currentUserId) {
        echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $message['Message']['id']], ['class' => 'delete-message delete-link', 'confirm' => __('Are you sure you want to delete this message?')]);
    }
    echo '</div>';
}
?>
