<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Message $Message
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Name is required'
            ),
            'length' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Name should be between 5 to 20 characters'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Email is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already registered'
            ),
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Password is required'
            )
        ),
        'confirm_password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Please confirm your password'
            ),
            'match' => array(
                'rule' => array('validatePasswordConfirmation'),
                'message' => 'Passwords do not match'
            )
        ),
        'photo' => array(
            'rule' => array(
                'extension', array('jpg','gif','png'),
                'message' => 'Please supply a valid image'
            ),
        ),
        'birthday' => array(
            'rule' => array('date'),
            'message' =>'Please enter a valid date'
        ),
        'gender' => array(
            'rule' => array('inList', array('male','female')),
            'message' => 'Please select a valid gender'
        ),
        'hobby' => array(
            'rule' => 'notBlank',
            'message' => 'Please describe your hobby'
        )
    );
    
    public function validatePasswordConfirmation($check) {
        if ($check['confirm_password'] === $this->data['User']['password']) {
            return true;
        }
        return false;
    }
}
