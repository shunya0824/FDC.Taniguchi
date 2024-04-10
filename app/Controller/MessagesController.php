<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('create');
    }

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


    public function create() {
        $this->autoRender = false; // ビューレンダリングを無効化
        if ($this->request->is('post')) {
            
            $userId = $this->Auth->user('id');
        
            $this->request->data['Message']['sender_id'] = $userId;
            $this->request->data['Message']['user_id'] = $userId; 
            $this->Message->create();
            // MessagesControllerのcreateアクション内
            if ($message = $this->Message->save($this->request->data)) {
                // ここでrecipientNameを取得する
                $recipientName = $this->Message->Recipient->field('username', ['id' => $this->request->data['Message']['recipient_id']]);
                
                $response = [
                    'success' => true,
                    'messageText' => $message['Message']['message_text'],
                    'recipientName' => $recipientName // レスポンスに含める
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
}
