<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\User;

/**
 * Сброс пароля
 */
class ResetPassword extends Model {
    
    public $_user;
    public $sms_code;
    
    public function __construct(User $user, $sms_code) {
        $this->_user = $user;
        $this->sms_code = $sms_code;
        parent::__construct();
    }
    
    public function changePassword() {
        
        // Генерируем новый пароль
        $new_password = preg_replace('/[^a-zA-Z]/', '', Yii::$app->security->generateRandomKey(32));
        
        if (!$this->sendSms($this->_user->user_mobile, $this->_user->user_login, $new_password)) {
            return false;
        }
        
        // Хешируем новый пароль
        $this->_user->user_password = Yii::$app->security->generatePasswordHash($new_password);
        $this->_user->save();
        
        Yii::$app->session->destroy();
            
        return true;
        
    }
    
    /*
     * Отправка СМС-кода на номер мобильного телефона
     */
    private function sendSms($phone, $user_login, $new_password) {
        
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $sms = Yii::$app->sms;
        $result = $sms->send_sms($phone,
                'Для вашей учетной записи был создан новый пароль. Используйте его при следующем входе в личный кабинет. '
                . 'Напоминаем, ваш логин: ' . $user_login . ', ваш новый пароль: ' . $new_password);
        if (!$sms->isSuccess($result)) {
//            return $sms->getError($result);
            return false;
        }
        return true;        
    }
    
    
}
