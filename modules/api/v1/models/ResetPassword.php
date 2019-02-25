<?php

    namespace app\modules\api\v1\models;
    use yii\base\Model;

/**
 * Сброс пароля
 */
class ResetPassword extends Model {
    
    public $phone;
    public $sms_code;
    
    public function rules() {
        return [
            [['phone', 'sms_code'], 'string', 'max' => 50],
        ];
    }
    
    
    
}
