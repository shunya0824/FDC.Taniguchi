<?php
echo $this->Html->script('messageDetails');
// Assuming $conversationDetails contains conversation-related data
// and $messages contains the messages to display initially.
?>

<div class="conversation-details">
    <h2>Conversation Details</h2>
    
    <!-- Reply Form -->
    <?php echo $this->Form->create('Message', ['url' => ['controller' => 'Messages', 'action' => 'reply', $conversationId]]); ?>
        <?php echo $this->Form->textarea('message_text', ['placeholder' => 'Write your reply here...']); ?>
        <?php echo $this->Form->submit('Send'); ?>
    <?php echo $this->Form->end(); ?>
</div>

<div id="messages">

    <?php foreach ($conversation['Message'] as $message): ?>
        <div class="message">
            <p><?php echo h($message['message_text']); ?></p>
            <small><?php echo h($message['created']); ?></small>
            <?php echo $this->Html->link('Delete', ['action' => 'delete', $message['id']], ['class' => 'delete', 'confirm' => 'Are you sure?']); ?>
        </div>
    <?php endforeach; ?>
    <!-- Show More Link -->

    <!-- Show More Link -->
    <?php if (!empty($hasNextPage) && $hasNextPage): ?>
        <button id="showMoreMessages" data-next-page="<?php echo $page + 1; ?>">Show More</button>
    <?php endif; ?>



</div>

<script>
// Insert the JavaScript code provided in the previous response here.
</script>
