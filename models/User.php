<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use app\models\PersonalAccount;
    use yii\web\UploadedFile;
    

/**
 * Пользователи системы / роли
 */
    
class User extends ActiveRecord implements IdentityInterface
{
    public $_account;
    
    /*
     * Статусы учетной записи пользователя
     * STATUS_DISABLED - пользователь не подтвердил регитрацию
     * STATUS_ENABLED - пользователь подтвердил регитрацию
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    const STATUS_BLOCK = 2;


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
            
            [['user_photo'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['user_photo'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            [['user_check_email', 'user_check_sms'], 'boolean'],
            
            ['_account', 'safe'],
        ];
    }

    /*
     * Свзязь с таблицей "Собственники"
     */    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'user_client_id']);
    }

    /*
     * Свзязь с таблицей "Арендаторы"
     */    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_id' => 'user_rent_id']);
    }
    
    
// return $this->hasMany(Tag::className(), ['id' => 'tag_id']) // связываем tag.id с tag_to_post_bind.tag_id
//            ->viaTable('tag_to_post_bind', ['post_id' => 'id']); // tag_to_post_bind.post_id с post.id    
//        
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['account_id' => 'account_id'])
                ->viaTable('account_to_users', ['user_id' => 'user_id']);
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
     *  Поиск по ID пользователя
     */
    public static function findByID($user_id) {
        return static::findOne(['user_id' => $user_id]);        
    }    
    
    /*
     * Поиск пользователя по параметрам
     * @user (ID user) / @username / @account (ID лицевого счета)
     */    
    public static function findByUser($user) {
        return static::find()
                ->andWhere([
                    'user_id' => $user,
                    'status' => self::STATUS_ENABLED])
                ->one();
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
     * Поиск ID клиента
     */
    public static function findByClientId($user_id) {
        return static::findOne(['' => $user_id]);
    }
    
//    /*
//     * Для связи таблицы пользователь и лицевой счет
//     */
//    public function setUserAccountId($account_id) {
//        $id = PersonalAccount::find()
//                ->andWhere(['account_number' => $account_id])
//                ->select('account_id')
//                ->asArray()
//                ->one();
//        
//        $this->user_account_id = $id['account_id'];
//    }

    /*
     * Для связи таблицы пользователь и собственник (поиск по номеру телефона)
     */
    public function setClientByPhone($account_number) {
//        $id = Clients::find()
//                ->andWhere(['clients_mobile' => $phone])
//                ->select('clients_id')
//                ->asArray()
//                ->one();
        
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_number])
                ->select('personal_clients_id')
                ->asArray()
                ->one();
        
        $this->user_client_id = $id['personal_clients_id'];
    }    
    
    /*
     * Загрузка фотографии в профиле пользователя
     */    
    public function uploadPhoto($file) {
        
        $current_image = $this->user_photo;
        
        if ($this->validate()) {
            if ($file) {
                $this->user_photo = $file;
                $dir = Yii::getAlias('images/users/');
                $file_name = $this->user_login . '_' . $this->user_photo->baseName . '.' . $this->user_photo->extension;
                $this->user_photo->saveAs($dir . $file_name);
                $this->user_photo = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->user_photo = $current_image;
            }
            
            return $this->save() ? true : false;
        }
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
