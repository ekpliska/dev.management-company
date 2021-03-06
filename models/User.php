<?php
    namespace app\models;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use yii\imagine\Image;
    use Imagine\Image\Box;
    use app\models\PersonalAccount;
    use app\models\Employees;
    use app\models\Token;
    use app\models\TokenPushMobile;
    

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
    const STATUS_BLOCK = 2;
    
    const SCENARIO_EDIT_PROFILE = 'edit user profile';
    const SCENARIO_EDIT_ADMINISTRATION_PROFILE = 'edit administration profile';
    const SCENARIO_EDIT_CLIENT_PROFILE = 'edit client profile';
    const SCENARIO_ADD_USER = 'add new user';
    
    /*
     * Дополнительные свойства для модели создания нового пользователя
     * 
     * @param integer $is_new Разрешение добавлять новости
     * @param integer $role Роль пользователя
     */
    public $is_new = false;
    public $role;

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
            [['user_login', 'user_email', 'user_mobile'], 'required'],
            
            [['user_email', 'user_mobile'], 'required', 'on' => self::SCENARIO_EDIT_PROFILE],
            
            [['user_photo'], 'file', 
                'extensions' => 'png, jpg, jpeg', 
                'maxFiles' => 5, 
                'maxSize' => 20 * 1024 * 1024,
                'mimeTypes' => 'image/*',                
            ],
            
            ['user_email', 'string', 'min' => 5, 'max' => 150, 'on' => self::SCENARIO_EDIT_PROFILE],
            ['user_email', 'email'],
            ['user_email', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Указанный электронный адрес в системе зарегистрирован',
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            
            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер телефона в системе зарегистрирован',
                'on' => self::SCENARIO_EDIT_PROFILE,
            ],
            
            [['user_mobile'], 'required', 'on' => self::SCENARIO_EDIT_ADMINISTRATION_PROFILE],
            
            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер телефона в системе зарегистрирован',
                'on' => self::SCENARIO_EDIT_ADMINISTRATION_PROFILE,
            ],

            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер телефона в системе зарегистрирован',
            ],
            
            ['user_email', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Указанный электронный адрес в системе зарегистрирован',
            ],
            
            
//            [['user_email'], 'required', 'on' => self::SCENARIO_EDIT_CLIENT_PROFILE],
            
            [['user_check_email'], 'boolean'],
            
            [['is_new', 'role'], 'safe'],
            
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
    
    /*
     * Связь через промежуточную таблицу
     */
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['account_id' => 'account_id'])
                ->viaTable('account_to_users', ['user_id' => 'user_id']);
    }
    
    /*
     * Связь с таблицей Сотрудники
     */
    public function getEmployee() {
        return $this->hasOne(Employees::className(), ['employee_id' => 'user_employee_id']);
    }
    
    /*
     * Связь с таблицей Токен, для авторизации по API
     */
    public function getTokens() {
        return $this->hasMany(Token::className(), ['user_uid' => 'user_id']);
    }
    
    /*
     * Сязь с таблицей Токенов для push рассылки
     */
    public function getPushToken() {
        return $this->hasMany(TokenPushMobile::className(), ['user_uid' => 'user_id']);
    }
        
    /*
     * Поиск экземпляра identity, используя ID пользователя со статусом подтверденной регистрации
     * Для поддержки состояние аутентификации через сессии
     */    
    public static function findIdentity($id) {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ENABLED]);
    }

    /*
     * Аутентификация на основе токена
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        
        return static::find()
            ->joinWith('tokens t')
            ->andWhere(['t.token' => $token])
            ->andWhere(['>', 't.expired_at', time()])
            ->one();
        
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
     * @user (ID user)
     */    
    public static function findByUser($user) {
        return static::find()
                ->andWhere([
                    'user_id' => $user,
                    'status' => self::STATUS_ENABLED])
                ->one();
    }
    
    public static function findByEmail($email) {
        
        return self::find()
                ->where(['user_email' => $email])
                ->asArray()
                ->one();
    }

    public static function findByPhone($phone) {
        
        return self::find()
                ->joinWith(['client', 'rent'])
                ->where(['user_mobile' => $phone])
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
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
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
     * Создаем хеш пароля четной записи
     */
    public function setUserPassword($password) {
        return $this->user_password = Yii::$app->security->generatePasswordHash($password);
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
        return static::findOne(['user_client_id' => $user_id]);
    }

    /*
     * Поиск ID сотрудника
     */
    public static function findByEmployeeId($employee_id) {
        return static::findOne(['user_employee_id' => $employee_id]);
    }
    
    /*
     * Для связи таблицы пользователь и собственник (поиск по номеру телефона)
     */
    public function setClientByPhone($account_number) {
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_number])
                ->select('personal_clients_id')
                ->asArray()
                ->one();
        
        $this->user_client_id = $id['personal_clients_id'];
    }
    
    /*
     * Массив статусов учетной записи
     */
    public static function arrayUserStatus() {
        return [
            self::STATUS_ENABLED => 'Активен',
            self::STATUS_DISABLED => 'Не авторизирован',
            self::STATUS_BLOCK => 'Заблокирован собственником',
        ];
    }
    /*
     * Статус учетной записи пользователя
     */
    public function getUserStatus() {
        
        return ArrayHelper::getValue(self::arrayUserStatus(), $this->status);
    }
    
    /*
     * Загрузка фотографии в профиле пользователя
     */    
    public function uploadPhoto($file) {
        
        $current_image = $this->user_photo;
        
        if ($this->validate()) {
            if ($file) {
                $this->user_photo = $file;
                $dir = Yii::getAlias('upload/users/');
                $file_name = $this->user_login . '_' . $this->user_photo->baseName . '.' . $this->user_photo->extension;
                $this->user_photo->saveAs($dir . $file_name);
                $this->user_photo = '/' . $dir . $file_name;
                
                $photo_path = Yii::getAlias('@webroot') . '/' . $dir . $file_name;
                $photo = Image::getImagine()->open($photo_path);
                $photo->thumbnail(new Box(200, 200))->save($photo_path, ['quality' => 40]);
                
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->user_photo = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /*
     * Получить список всех доступнвых ролей
     */
    public static function getRoles() {
        
        $list = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($list, 'name', 'description');
        
    }    
    
    /*
     * Назначение роли пользователю
     * 
     * @param string $role Роль
     * @param integer $user_id ID пользователя
     */
    public function setRole($role, $user_id) {
        $_role = Yii::$app->authManager->getRole($role);
        Yii::$app->authManager->assign($_role, $user_id);
    }
    
    /*
     * Получить имя роли пользователя
     */
    public static function getRole($name) {
        return ArrayHelper::getValue(self::getRoles(), $name);
    }
        
    /*
     * Аватар пользователя
     */
    public function getPhoto() {
        
        if (empty($this->user_photo)) {
            return Yii::getAlias('@web') . '/images/no-avatar.jpg';
        }
        return Yii::getAlias('@web') . $this->user_photo;
        
    }
    
    /*
     * Получить имя собсвенника, арендатора, сотрудника
     */
    public static function getUserName() {
        
        $name = 'Не определено';
        
        $quesry = self::find()
                ->with(['client', 'rent', 'employee'])
                ->where(['user_id' => Yii::$app->user->id])
                ->asArray()
                ->one();
        
        if (!empty($quesry['client'])) {
            $name = $quesry['client']['clients_name'];
        } elseif (!empty($quesry['rent'])) {
            $name = $quesry['rent']['rents_name'];
        } elseif (!empty($quesry['employee'])) {
            $name = $quesry['employee']['employee_name'];
        }
        
        return $name;
        
    }
    
    public function afterSave($insert, $changedAttributes) {
        
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            // Удаляем токены авторизации для мобильных устройств для пользователей со статусом Заблокирован
            if ($this->status == User::STATUS_BLOCK) {
                Token::deleteAll(['user_uid' => $this->user_id]);
            }
        }
        
    }

    
    public function afterDelete() {
        
        parent::afterDelete();
        
        Yii::$app->db->createCommand('DELETE FROM auth_assignment WHERE user_id=:user_id')
            ->bindValue(':user_id', $this->user_id)
            ->execute();
        
    }

    /*
     * Настройка полей для форм
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User Id',
            'user_login' => 'Логин пользователя',
            'user_password' => 'Пароль пользователя',
            'user_email' => 'Электронная почта',
            'user_mobile' => 'Мобильный телефон',
            'user_photo' => 'Аватар',
            'user_check_email' => 'Разрешить e-mail оповещения',
            'user_authkey' => 'User Authkey',
            'date_create' => 'Дата регистрации',
            'updated_at' => 'Дата редактирования',
            'status' => 'Статус',
            'date_login' => 'Дата последнего входа',
            'is_new' => 'Добавлять новости',
            'role' => 'Роль',
        ];
    }
    
}
