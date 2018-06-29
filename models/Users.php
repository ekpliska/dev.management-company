<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;
    use yii\behaviors\TimestampBehavior;
    use yii\web\IdentityInterface;

/**
 * Пользователи системы / роли
 */
class Users extends ActiveRecord
{

    public function behaviors() {
        return [
            [
                /*
                 * Автоматическая генерация даты создания аккуанта
                 * Автоматическая генерация даты последнего логина пользователя
                 */
                'class' => 'TimestampBehavior::className()',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_last_login'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_last_login'],
                ],
            ]
        ];
    }

    /*
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'Users';
    }

    /*
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'user_login', 'user_password', 
                'date_create', 'date_last_login'], 'required'],
            [['user_login', 'user_check_email', 'user_check_sms', 'user_account_id'], 'integer'],
            [['user_password', 'user_photo', 'user_authkey'], 'string', 'max' => 255],
            [['date_create', 'date_last_login'], 'integer'],
        ];
    }
    
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['user_login' => 'account_id']);
    }
    
    /*
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_login' => 'Логин',
            'user_password' => 'Пароль',
            'user_photo' => 'User Photo',
            'user_check_email' => 'User Check Email',
            'user_check_sms' => 'User Check Sms',
        ];
    }
}
