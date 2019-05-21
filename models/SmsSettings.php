<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

/**
 * Настрофка СМС оповещений
 */
class SmsSettings extends ActiveRecord {
    
    const TYPE_NOTICE_REGISTER = 'register';
    const TYPE_NOTICE_REPEAT_SMS = 'repeat sms';
    const TYPE_NOTICE_PARTICIPANT_VOTING = 'participant at voting';
    const TYPE_NOTICE_RECOVERY_PASSWORD = 'recovery password';
    const TYPE_NOTICE_NEW_PASSWORD = 'new password';
    const TYPE_NOTICE_CHANGE_PASSWORD = 'change password';
    const TYPE_NOTICE_CHANGE_MOBILE = 'change mobile phone';
    const TYPE_NOTICE_SIGN_IN = 'sign in';
    const TYPE_RESET_PIN_CODE = 'reset pin-code';
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'sms_settings';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['sms_code', 'sms_text'], 'required'],
            [['sms_text'], 'string'],
            [['sms_code'], 'string', 'max' => 70],
        ];
    }
    
    public function getTypeNotices() {
        
        return [
            self::TYPE_NOTICE_REGISTER => 'Регистрация на портале',
            self::TYPE_NOTICE_REPEAT_SMS => 'Повторный СМС код',
            self::TYPE_NOTICE_PARTICIPANT_VOTING => 'Принять участние в голосовании',
            self::TYPE_NOTICE_RECOVERY_PASSWORD => 'Подтверждение восстановления пароля',
            self::TYPE_NOTICE_CHANGE_PASSWORD => 'Подтверждение смены пароля',
            self::TYPE_NOTICE_CHANGE_MOBILE => 'Подтверждение смены номера мобильного телефона',
            self::TYPE_NOTICE_SIGN_IN => 'Подтверждение входа',
            self::TYPE_RESET_PIN_CODE => 'Восстановление PIN-кода на мобильном устройстве',
        ];
        
    }
    
    public function getTypeName($type) {
        
        return ArrayHelper::getValue($this->getTypeNotices(), $type);
        
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'sms_code' => 'Sms Code',
            'sms_text' => 'Sms Text',
        ];
    }
}
