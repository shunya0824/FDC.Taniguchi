<?php
echo $this->Html->script('//code.jquery.com/jquery-3.6.0.min.js');
echo $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.min.js');
echo $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

$this->Html->scriptBlock('
    $(function() {
        $("#UserBirthday").datepicker();

        $("#UserPhoto").change(function() {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#photo-preview").attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    });
', array('inline' => false));

echo $this->Form->create('User', array('type' => 'file'));
echo $this->Form->input('photo', array('type' => 'file', 'label' => 'Profile Photo', 'id' => 'UserPhoto'));
echo $this->Html->image('', array('id' => 'photo-preview', 'alt' => 'Photo Preview', 'style' => 'width:150px;height:150px;'));
echo $this->Form->input('name');
echo $this->Form->input('birthday', array('type' => 'text', 'id' => 'UserBirthday'));
echo $this->Form->input('gender', array('type' => 'radio', 'options' => array('M' => 'Male', 'F' => 'Female')));
echo $this->Form->input('hobby');
echo $this->Form->end('Update');
?>
