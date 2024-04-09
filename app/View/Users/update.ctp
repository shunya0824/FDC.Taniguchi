<?php
// jQueryとjQuery UIの読み込み
echo $this->Html->script('//code.jquery.com/jquery-3.6.0.min.js');
echo $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.min.js');
echo $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

// カスタムJavaScriptファイルの読み込み
echo $this->Html->script('main');

// フォームの作成
echo $this->Form->create('User', array('type' => 'file'));

// 写真アップロードフィールド
echo $this->Form->input('photo', array('type' => 'file', 'label' => 'Profile Photo', 'id' => 'UserPhoto'));

// 写真プレビュー
if (!empty($this->request->data['User']['photo'])) {
    echo $this->Html->image('/img/user_photos/' . $this->request->data['User']['photo'], array('id' => 'photo-preview', 'style' => 'width:150px;height:150px;'));
} else {
    echo $this->Html->image(null, array('id' => 'photo-preview', 'alt' => 'Photo Preview', 'style' => 'width:150px;height:150px;'));
}

// ユーザー情報フィールド
echo $this->Form->input('name');
echo $this->Form->input('birthday', array('type' => 'text', 'id' => 'UserBirthday'));
echo $this->Form->input('gender', array(
    'type' => 'radio',
    'options' => array('male' => 'Male', 'female' => 'Female'),
));
echo $this->Form->input('hobby');

// 現在のパスワード確認フィールド (プロファイル更新の場合)


echo $this->Form->end('Update');
?>
