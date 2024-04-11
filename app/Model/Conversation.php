<?php
App::uses('AppModel', 'Model');
// In your Conversation model
class Conversation extends AppModel {
    public $actsAs = array('Containable');
    public $hasMany = array(
        'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'conversation_id',
            'order' => 'Message.created ASC' // Ensures chronological order
        )
    );
}
?>
