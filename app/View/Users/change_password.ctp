<?php
// View/Users/change_password.ctp
echo $this->Form->create('User', ['url' => ['controller' => 'Users', 'action' => 'change_password']]);

echo $this->Form->input('old_password', array('type' => 'password', 'label' => 'Current Password'));
echo $this->Form->input('new_password', array('type' => 'password', 'label' => 'New Password'));
echo $this->Form->input('new_password_confirm', array('type' => 'password', 'label' => 'Confirm New Password'));
echo $this->Form->end('Change Password');

?>