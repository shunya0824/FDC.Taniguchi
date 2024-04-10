<?php echo $this->Html->css('messages'); ?>

<div class="messages index">
    <h2><?php echo __('Messages'); ?></h2>

    <?php echo $this->Html->link('New Message', '#', ['class' => 'new-message-btn']); ?>

    <div class="message-list">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo ($message['Message']['sender_id'] == $currentUserId) ? 'sent' : 'received'; ?>">
                <?php if ($message['Message']['sender_id'] == $currentUserId): ?>
                    <p>To: <?php echo h($message['Recipient']['username']); ?></p>
                <?php else: ?>
                    <p>From: <?php echo h($message['Sender']['username']); ?></p>
                <?php endif; ?>
                <p><?php echo h($message['Message']['message_text']); ?></p>
                <small><?php echo h($message['Message']['created']); ?></small>
                <?php if ($message['Message']['sender_id'] == $currentUserId): ?>
                    <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $message['Message']['id']], ['class' => 'delete-message delete-link'], __('Are you sure you want to delete this message?')); ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ここで "Show More" ボタンを追加 -->
    <?php
    // 現在のページ番号を取得、設定されていない場合は1とします
    $currentPage = $this->request->query('page') ?? 1;
    // 次のページ番号
    $nextPage = $currentPage + 1;
    ?>

    <?php if ($this->Paginator->hasNext()): ?>
        <?php echo $this->Html->link('Show More', '#', [
            'class' => 'show-more-button',
            'data-page' => $nextPage
        ]); ?>
    <?php endif; ?>



</div>


<!-- <div class="messages index">
    <h2><?php echo __('Messages'); ?></h2>

    <?php echo $this->Html->link('New Message', '#', ['class' => 'new-message-btn']); ?>

    <div class="message-list">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo ($message['Message']['sender_id'] == $currentUserId) ? 'sent' : 'received'; ?>">

                <p>From: <?php echo h($message['Sender']['username']); ?></p>
                <p><?php echo h($message['Message']['message_text']); ?></p>
                <small><?php echo h($message['Message']['created']); ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="message-list">
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo ($message['Message']['sender_id'] == $currentUserId) ? 'sent' : 'received'; ?>">
                <div class="message-details">
                    <p>
                        <?php if ($message['Message']['sender_id'] == $currentUserId): ?>
                            To: <?php echo h($message['Recipient']['username']); ?>
                        <?php else: ?>
                            From: <?php echo h($message['Sender']['username']); ?>
                        <?php endif; ?>
                    </p>
                    <div class="message-content">
                        <p><?php echo h($message['Message']['message_text']); ?></p>
                        <small><?php echo h($message['Message']['created']); ?></small>
                    </div>
                    <?php if ($message['Message']['sender_id'] == $currentUserId): ?>
                        <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $message['Message']['id']], ['class' => 'delete-message delete-link'], __('Are you sure you want to delete this message?')); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div> -->

<div id="newMessageForm" style="display:none;">
    <?php echo $this->Form->create('Message', ['url' => ['action' => 'create'], 'id' => 'newMessageFormAjax']); ?>
        <!-- ユーザー名の入力フィールド -->
        <?php echo $this->Form->input('username', ['type' => 'text', 'label' => 'To who', 'id' => 'recipientUsername']); ?>
        <!-- 隠しフィールドでrecipient_idを保持 -->
        <?php echo $this->Form->hidden('recipient_id', ['id' => 'recipientId']); ?>
        <!-- メッセージテキストの入力フィールド -->
        <?php echo $this->Form->input('message_text', ['type' => 'textarea', 'label' => 'Text']); ?>
        <?php echo $this->Form->button('Send'); ?>
    <?php echo $this->Form->end(); ?>
</div>



<?php echo $this->Html->script('message_form'); ?>
