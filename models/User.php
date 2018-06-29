<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    use yii\behaviors\TimestampBehavior;
    use app\models\PersonalAccount;
    use Yii;

/**
 * Пользователи системы / роли
 */
    
class User extends ActiveRecord implements IdentityInterface
{
    /*
     * Статусы учетной записи пользователя
     * STATUS_DISABLED - пользователь не подтвердил регитрацию
     * STATUS_ENABLED - пользователь подтвердил регитрацию
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    
    /*
     *  Таблица из БД
     */
    public static function tableName() {
        return 'Users';        
    }

    /*
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['user_login', 'user_password'], 'required'],
            ['user_login', 'integer'],
            
            ['user_email', 'string', 'max' => 100],
            ['user_email', 'email'],
                
            ['user_mobile', 'string', 'max' => 50],
            
            [['user_check_email', 'user_check_sms'], 'integer'],
            
            [['user_password', 'user_photo', 'user_authkey'], 'string', 'max' => 255],
            
            ['status', 'integer'],
            
        ];
    }

    /*
     * Свзязь с таблицей "Лицевой счет"
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['user_login' => 'account_id']);
    }
    
    /*
     * Запись даты регистрации, статус учетной записи
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->date_create = date('Y-m-d H:i:s');
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * Поиск экземпляра identity, используя ID пользователя со статусом подтверденной регистрации
     * Для поддержки состояние аутентификации через сессии
     */    
    public static function findIdentity($id) {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    /*
     * Аутентификация на основе токена, требуется объявить
     * В проекте не используется    
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        // echo
    }

    /*
     *  Поиск имени пользователя    
     */
    public static function findByUsername($username) {
        return static::findOne(['user_login' => $username]);
    }

    /*
     *  Получить ID пользователя
     */
    public function getId() {
        return $this->user_id;
    }

    /*
     *  Получить ключ, используемый для cookie аутентификации
     */
    public function getAuthKey() {
        return $this->user_authkey;
    }

    /*
     *  Проверка ключа для аутентификации на основе cookie
     */
    public function validateAuthKey($autKey) {
        return $this->user_authkey === $authKey;
    }

    /*
     *  Проверка валидации пароля пользователя
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->user_password);
    }

    /*
     *  Генерация ключа в виде случайной строки    
     */
    public function generateAuthKey() {
        $this->user_authkey = Yii::$app->security->generateRandomString();
    }
    
    /*
     * Генерация ключа email_confirm_token для подтверждения регистрации по почте
     * Присваивается каждому пользователю при регистрации
     */
    public function generateEmailConfirmToken() {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }
    
    /*
     * Поиск пользователя по сгенерированному ключу
     * Если запрашиваемы ключ найден, то меняем статус пользователя на STATUS_ENABLED
     */
    public static function findByEmailConfirmToken($email_confirm_token) {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => User::STATUS_DISABLED]);
    }
    
    public function confirmEmail($token) {
        $user = self::findByEmailConfirmToken($token);
        $user->status = self::STATUS_ENABLED;
        $user->email_confirm_token = null;
        
    }
    
    /*
     * Настройка полей для форм
     */
    public function getAttributeLabel() {
        return [
            'user_id' => 'User Id',
            'user_login' => 'Логин пользователя',
            'user_password' => 'Пароль пользователя',
            'user_photo' => 'Аватар',
            'user_check_email' => 'Email рассылка',
            'user_check_sms' => 'SMS оповещения',
            'user_authkey' => 'User Authkey',
            'date_create' => 'Дата регистрации',
            'date_last_login' => 'Дата последнего логина',
        ];
    }
    
}
