<h1>Thank you for registering!</h1>
<?php
echo $this->Session->flash();
?>
<p>Your registration is now complete.</p>
<?php echo $this->Html->link('Back to homepage', array('controller' => 'pages', 'action' => 'display', 'home')); ?>
