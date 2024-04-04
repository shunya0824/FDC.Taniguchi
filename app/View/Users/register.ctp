<?php
echo $this->Form->create('User');
echo $this->Form->input('username');
echo $this->Form->input('email');
echo $this->Form->input('password', array('type' => 'password'));
echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => 'Confirm Password'));
echo $this->Form->end('Register');
?>