<?php

echo $this->Form->create('User');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('new_email', array('label' => 'New Email Address'));
echo $this->Form->end('Update Email');

?>
