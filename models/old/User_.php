<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
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
        return 'user';        
    }

    /*
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'username', 'password_hash', 'email'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['username', 'email_confirm_token', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /*
     * Свзязь с таблицей "Лицевой счет"
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['user_login' => 'account_id']);
    }
    
    /*
     * Поиск экземпляра identity, используя ID пользователя со статусом подтверденной регистрации
     * Для поддержки состояние аутентификации через сессии
     */    
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    /*
     * Аутентификация на основе токена, требуется объявить
     * В проекте не используется    
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \yii\base\NotSupportedException('Аутентификация на основе токена не поддерживается');
    }

    /*
     *  Поиск имени пользователя    
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /*
     *  Получить ID пользователя
     */
    public function getId() {
        return $this->id;
    }

    /*
     *  Получить ключ, используемый для cookie аутентификации
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /*
     *  Проверка ключа для аутентификации на основе cookie
     */
    public function validateAuthKey($autKey) {
        return $this->auth_key === $authKey;
    }

    /*
     *  Проверка валидации пароля пользователя
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /*
     *  Генерация ключа в виде случайной строки    
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
    
    /*
     * Настройка полей для форм
     */    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'email_confirm_token' => 'Email Confirm Token',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }
}
