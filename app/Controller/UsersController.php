<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        //temporaily allow for development phase
        $this->Auth->allow('thankyou','update','change_email','autocomplete');
    }

    public function index() {
        $users = $this->User->find('all');
        $this->set('users',$users);
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
              
                return $this->redirect($this->Auth->redirectUrl());
            }
        
            $this->Session->setFlash(__('Username or password is incorrect'), 'default', [], 'auth');
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function register() {
        if ($this->Auth->loggedIn()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        



        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved.'));
                return $this->redirect(array('action' => 'thankyou'));
            }
            $this->Session->setFlash(__('Unable to add the user.'));
        }
    }

    public function thankyou() {
    }

    public function change_email() {
        $id = $this->Auth->user('id'); 
        if (!$id) {
            $this->Session->setFlash(__('You are not logged in.'));
            return $this->redirect(array('action' => 'login'));
        }
    
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }
    
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
            if ($this->User->saveField('email', $this->request->data['User']['new_email'])) {
                $this->Session->setFlash(__('Your email has been updated.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Unable to update your email.'));
            }
        }
    
        $this->request->data = $user;
    }
    
    public function change_password() {
        $userId = $this->Auth->user('id'); 
        if (!$userId) {
            $this->Session->setFlash(__('You are not logged in.'));
            return $this->redirect(array('action' => 'login'));
        }
        
        if ($this->request->is(array('post', 'put'))) {
            $user = $this->User->findById($userId);
            if (!$user) {
                throw new NotFoundException(__('Invalid user'));
            }
            
            // 現在のパスワードが正しいか確認
            $currentPasswordHash = AuthComponent::password($this->request->data['User']['old_password']);
            if ($user['User']['password'] !== $currentPasswordHash) {
                $this->Session->setFlash(__('The current password is incorrect.'));
                return;
            }
    
            // 新しいパスワードと確認用のパスワードが一致するか確認
            if ($this->request->data['User']['new_password'] !== $this->request->data['User']['new_password_confirm']) {
                $this->Session->setFlash(__('The new passwords do not match.'));
                return;
            }
    
            // パスワードを更新
            $this->User->id = $userId;
            if ($this->User->saveField('password', AuthComponent::password($this->request->data['User']['new_password']))) {
                $this->Session->setFlash(__('Your password has been updated.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Unable to update your password.'));
            }
        }
    }

    public function view_profile($id = null) {
        $userId = $id ? $id : $this->Auth->user('id');
        if(!$userId) {
            $this->Session->setFlash(__('You are not logged in.'));
            return $this->redirect(array('action' => 'login'));
        }

        $user = $this->User->findById($userId);
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }

        $this->set('user', $user);
    }

    public function update($id = null) {
        $id = $id ?: $this->Auth->user('id');
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }
    
        if ($this->request->is(['post', 'put'])) {
            $existingPhoto = !empty($user['User']['photo']) ? $user['User']['photo'] : null;
    
            if (!empty($this->request->data['User']['photo']['tmp_name'])) {
                $file = $this->request->data['User']['photo'];
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                $newFilename = "{$id}_" . time() . ".{$ext}";
    
                if (move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img' . DS . 'user_photos' . DS . $newFilename)) {
                    $this->request->data['User']['photo'] = $newFilename;
                } else {
                    $this->Session->setFlash(__('Unable to upload photo.'));
                    $this->redirect(['action' => 'update', $id]);
                    return;
                }
            } else {
                $this->request->data['User']['photo'] = $existingPhoto;
            }
    
            $this->User->id = $id;
            if ($this->User->save($this->request->data, true, ['username', 'name', 'email', 'birthday', 'gender', 'hobby', 'photo'])) {
                $this->Session->setFlash(__('Your profile has been updated.'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('Unable to update your profile.'));
            }
        } else {
            $this->request->data = $user;
        }
    }

    public function autocomplete() {
        $this->autoRender = false;
        $term = $this->request->query('term');
        $users = $this->User->find('all', [
            'conditions' => [
                'User.username LIKE' => '%' . $term . '%'
            ],
            'fields' => ['User.id', 'User.username'],
        ]);
      

    
        $result = array_map(function ($user) {
            return ['label' => $user['User']['username'], 'value' => $user['User']['id']];
        }, $users);
    
        echo json_encode($result);
    }
    

    // public function autocomplete() {
    //     $this->autoRender = false; // 自動レンダリングを無効化
    //     $term = $this->request->query('term');
    //     $users = $this->User->find('all', [
    //         'conditions' => [
    //             'User.username LIKE' => '%' . $term . '%'
    //         ],
    //         'fields' => ['User.id', 'User.username'],
    //     ]);
    //     debug($users);
    //     $result = [];
    //     foreach ($users as $user) {
    //         $result[] = ['label' => $user['User']['username'], 'value' => $user['User']['id']];
    //     }
    //     echo json_encode($result);
    // }
}
