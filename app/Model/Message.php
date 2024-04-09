<?php
App::uses('AppModel', 'Model');

class Message extends AppModel {

    public $useTable = 'messages'; // カスタムテーブル名が必要な場合

    public $belongsTo = array(
        'User' => array(
            'className' =>'User',
            'foreignKey' => 'user_id'
        ),
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id'
        ),
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id'
        ),
        'Conversation' => array(
            'className' => 'Conversation',
            'foreignKey' => 'conversation_id'
        )
    );

    public $validate = array(
        'message_text' => array(
            'rule' => 'notBlank',
            'message' => 'Message text cannot be empty',
        ),
        'sender_id' => array(
            'rule' => 'numeric',
            'message' => 'Sender ID must be numeric',
        ),
        'recipient_id' => array(
            'rule' => 'numeric',
            'message' => 'Recipient ID must be numeric',
        ),
    );

    // その他のカスタム関数やビヘイビアはここに追加します
}
?>
