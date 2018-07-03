<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use app\models\PersonalAccount;
    

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
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
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
            ['user_login', 'required'],
            
            [['user_photo'], 'file', 'extensions' => 'png, jpg'],
            [['user_photo'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            [['user_check_email', 'user_check_sms'], 'boolean'],
        ];
    }

    /*
     * Свзязь с таблицей "Лицевой счет"
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'user_account_id']);
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
        throw new \yii\base\NotSupportedException('Аутентификация на основе токена не поддерживается');
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
    
    /*
     * Поиск пользователя по email
     */
    public static function findByEmail($user_email) {
        return static::findOne(['user_email' => $user_email, 'status' => self::STATUS_ENABLED]);
    }
    
    /*
     * Для связи таблицы пользователь и лицевой счет
     */
    public function setUserAccountId($account_id) {
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_id])
                ->select('account_id')
                ->asArray()
                ->one();
        
        $this->user_account_id = $id['account_id'];
    }
    
    public function editProfile() {
        //
    }
    
    /*
     * Настройка полей для форм
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User Id',
            'user_login' => 'Логин пользователя',
            'user_password' => 'Пароль пользователя',
            'user_photo' => 'Аватар',
            'user_check_email' => 'Разрешить e-mail оповещения',
            'user_check_sms' => 'Разрешить SMS оповещения',
            'user_authkey' => 'User Authkey',
            'date_create' => 'Дата регистрации',
            'date_last_login' => 'Дата последнего логина',
        ];
    }
    
}
