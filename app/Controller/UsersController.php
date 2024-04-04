<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('thankyou','update');
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
    
    public function update($id = null) {
        if (!$id) {
            $id = $this->Auth->user('id');
        }
        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }
    
        if ($this->request->is(array('post', 'put'))) {
            if (!empty($this->request->data['User']['photo']['tmp_name'])) {
                $file = $this->request->data['User']['photo'];
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                $newFilename = $id . '_' . time() . '.' . $ext;
                
                if (move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img' . DS . 'user_photos' . DS . $newFilename)) {
                    $this->request->data['User']['photo'] = $newFilename;
                } else {
                    $this->Session->setFlash(__('Unable to upload photo.'));
                    unset($this->request->data['User']['photo']);
                }
            } else {
                unset($this->request->data['User']['photo']);
            }
    
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Your profile has been updated.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Unable to update your profile.'));
            }
        } else {
            $this->request->data = $this->User->findById($id);
        }
    }
    
}
