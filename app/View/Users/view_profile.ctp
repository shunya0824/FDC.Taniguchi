<?php echo $this->Html->css('style.css'); ?>

<div class="user-profile">
    <div class="user-profile-header">
        <!-- User photo section -->
        <?php if (!empty($user['User']['photo'])): ?>
            <img class="user-photo" src="<?php echo $this->Html->url('/img/user_photos/' . h($user['User']['photo'])); ?>" alt="User Photo">
        <?php else: ?>
            <!-- If no photo is available, show a placeholder silhouette -->
            <img class="user-photo" src="<?php echo $this->Html->url('/img/default_avatar.png'); ?>" alt="Default User Avatar">
        <?php endif; ?>

        <div class="user-details">
            <!-- User details section immediately beside the photo -->
            <h3><?php echo h($user['User']['username']); ?></h3>
            <p><strong>Gender:</strong> <?php echo h($user['User']['gender']); ?></p>
            <p><strong>Birthday:</strong> <?php echo h($user['User']['birthday']); ?></p>
            <p><strong>Joined:</strong> <?php echo h($user['User']['created']); ?></p>
            <p><strong>Last Login:</strong> <?php echo h($user['User']['last_login_time']); ?></p>
        </div>
    </div>

    <!-- Hobby section below the photo and details -->
    <div class="user-hobby">
        <strong>Hobby:</strong> <?php echo nl2br(h($user['User']['hobby'])); ?>
    </div>
</div>