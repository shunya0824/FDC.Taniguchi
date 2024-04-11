<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('create');
    }

    public $helpers = ['Html', 'Form', 'Url']; // Add 'Url' to the array of loaded helpers


    public function index() {
        $currentUserId = $this->Auth->user('id');
        $this->Paginator->settings = [
            'Message' => [
                'limit' => 4,
                'order' => ['Message.created' => 'desc'],
                'conditions' => [
                    'OR' => [
                        'Message.sender_id' => $currentUserId,
                        'Message.recipient_id' => $currentUserId
                    ]
                ],
                'contain' => ['Sender','Recipient']
            ]
        ];


        $messages = $this->Paginator->paginate('Message');

        $this->set(compact('messages', 'currentUserId'));

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
            $this->render('/Elements/messages_list');
        }
    }


    // public function create() {
    //     $this->autoRender = false; // ビューレンダリングを無効化
    //     if ($this->request->is('post')) {
            
    //         $userId = $this->Auth->user('id');
    //         $data = $this->request->data;

    //         if (empty($data['Message']['conversation_id'])) {
    //             $this->Message->Conversation->create();
    //             $conversation = $this->Message->Conversation->save();
    //             $data['Message']['conversation_id'] = $conversation['Conversation']['id'];
    //         }

    //         $data['Message']['sender_id'] = $userId;

    //         $this->Message->create();
    //         // MessagesControllerのcreateアクション内
    //         if ($message = $this->Message->save($data)) {
    //             // ここでrecipientNameを取得する
    //             $recipientName = $this->Message->Recipient->field('username', ['id' => $data['Message']['recipient_id']]);
                
    //             $response = [
    //                 'success' => true,
    //                 'messageText' => $message['Message']['message_text'],
    //                 'recipientName' => $recipientName // レスポンスに含める
    //             ];
        
    //         } else {
    //             $response = ['success' => false, 'message' => 'Unable to send your message.'];
    //         }
    //         echo json_encode($response);
    //     }
    // }
    
    

    public function create() {
        $this->autoRender = false; // Disable view rendering
        if ($this->request->is('post')) {
            $userId = $this->Auth->user('id');
            // CakeLog::write('debug', 'Debugging user_id: ' . print_r($userId, true));

            $data = $this->request->data;

            // Check if it's a new conversation or a reply
            if (empty($data['Message']['conversation_id'])) {
                $this->Message->Conversation->create();
                $conversation = $this->Message->Conversation->save();
                $data['Message']['conversation_id'] = $conversation['Conversation']['id'];
            } // If 'conversation_id' is provided, it's a reply to an existing conversation

            $data['Message']['sender_id'] = $userId;
            $data['Message']['user_id'] = $userId; // Make sure this is included if it's necessary for your schema.

            // CakeLog::write('debug', 'Debugging user_id: ' . print_r($userId, true));

            $this->Message->create();

            if ($message = $this->Message->save($data)) {
                CakeLog::write('debug', 'Save failed with errors: ' . print_r($this->Message->validationErrors, true));
                $recipientName = $this->Message->Recipient->field('username', ['id' => $data['Message']['recipient_id']]);
                $response = [
                    'success' => true,
                    'messageText' => $message['Message']['message_text'],
                    'recipientName' => $recipientName // Include recipient name in the response
                ];
            } else {
                $response = ['success' => false, 'message' => 'Unable to send your message.'];
            }
            echo json_encode($response);
        }
    }


    public function delete($id = null) {
        $this->request->allowMethod('post', 'delete');
        if (!$this->Message->exists($id)) {
            throw new NotFoundException(__('Invalid message'));
        }
        if ($this->Message->delete($id)) {
            $this->Session->setFlash(__('The message has been deleted.'));
        } else {
            $this->Session->setFlash(__('The message could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function messageDetails($conversationId = null) {
        $this->loadModel('Conversation');
        $currentUserId = $this->Auth->user('id');

        if (!$this->Conversation->exists($conversationId)) {
            throw new NotFoundException(__('Invalid conversation'));
        }

        $conversation = $this->Conversation->find('first',array(
            'conditions' => array('Conversation.id' => $conversationId),
            'contain' => array(
                'Message' => array(
                    'limit' => 10, // 10件までのメッセージを取得
                    'order' => 'Message.created ASC',
                    'Sender',
                    'Recipient'
                )
            )
        ));
        if ($this->request->is('post') || $this->request->is('ajax')) {
            $this->request->data['Message']['conversation_id'] = $conversationId;
            $this->request->data['Message']['sender_id'] = $currentUserId;

            if ($this->Message->save($this->request->data)) {
                $this->Session->setFlash(__('The message has been saved.'));
                if ($this->request->is('ajax')) {
                    $this->autoRender = false;
                    echo json_encode(array('status' => 'success'));
                    return;
                }
            } else {
                if ($this->request->is('ajax')) {
                    $this->autoRender = false;
                    echo json_encode(array('status' => 'failure'));
                    return;
                } 
                $this->Session->setFlash(__('The message could not be saved. Please, try again.'));
            }
        }
        $this->set('conversation', $conversation);
        $this->set('conversationId', $conversationId);
    }
}
