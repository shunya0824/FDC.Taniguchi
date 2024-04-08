<h2>User Profile</h2>

<div>
    <h3><?php echo h($user['User']['username']); ?></h3>
    <p><strong>Gender:</strong> <?php echo h($user['User']['gender']); ?></p>
    <p><strong>Birthday:</strong> <?php echo h($user['User']['birthday']); ?></p>
    <p><strong>Joined:</strong> <?php echo h($user['User']['created']); ?></p>
    <p><strong>Last Login:</strong> <?php echo h($user['User']['last_login_time']); ?></p>
    <p><strong>Hobby:</strong> <?php echo nl2br(h($user['User']['hobby'])); ?></p>

    <?php if ($user['User']['photo']): ?>
        <div>
            <h4>Photo:</h4>
            <img src="<?php echo $this->Html->url('/img/user_photos/' . $user['User']['photo']); ?>" alt="User Photo">
        </div>
    <?php endif; ?>
</div>
