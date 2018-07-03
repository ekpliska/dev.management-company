<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use yii\web\UploadedFile;

/*
 * Модель для редактирования профиля, клиентом
 */     
class UserForm extends Model {
    
    public $user_photo;
    public $user_check_email;
    public $user_check_sms;
    
    public function rules() {
        return [
            ['user_photo', 'file'],
            ['user_photo', 'extensions' => ['png, jpg', 'jpeg']],
            ['user_photo', 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            [['user_check_email', 'user_check_sms'], 'bollean'],
        ];
    }
    
    public function updateProfile() {
        
    }
    
    public function editProfile() {
        return false;
    }
}
