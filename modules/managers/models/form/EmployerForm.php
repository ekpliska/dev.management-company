<?php
    
    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\Employers;

/**
 * Новый Диспетчер
 */
class EmployerForm extends Model {
    
    // User info
    public $username;
    public $password;
    public $password_repeat;
    public $mobile;
    public $email;
    public $photo;
    public $role;
    public $rule;
    
    // dispatcher info
    public $surname;
    public $name;
    public $second_name;
    public $gender;
    public $department;
    public $post;
    
    // rules
    public $is_new = false;
    
    public function rules() {
        return [
            
            [[
                'username', 'password', 'password_repeat', 'mobile', 'email', 'role', 'rule' ,
                'surname', 'name', 'second_name', 'gender', 'department', 'post',], 
                'required'],
            
            ['username', 'string', 'min' => 3, 'max' => 50],
            
            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 12],
            ['password', 'compare', 'message' => 'Указанные пароли не совпадают!'],
            [['username', 'password', 'password_repeat'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],            
            
            ['email', 'match',
                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],
            
            [
                'username', 'unique', 
                'targetClass' => User::className(), 
                'targetAttribute' => 'user_login', 
                'message' => 'Пользователь с введенным лицевым счетом в системе уже зарегистрирован'
            ],
            
            [
                'email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Указанный номер телефона в системе зарегистирован'
            ],
            
            ['mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            [['photo'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['photo'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            [['surname', 'name', 'second_name'], 'filter', 'filter' => 'trim'],
            [['surname', 'name', 'second_name'], 'string', 'min' => 3, 'max' => 50],
            [
                ['surname', 'name', 'second_name'], 
                'match', 
                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
        ];
    }
    
    public function addDispatcher() {
        
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->validate()) {

                $employer = new Employers();

                $employer->employers_name = $this->name;
                $employer->employers_surname = $this->surname;
                $employer->employers_second_name = $this->surname;
                $employer->employers_department_id = $this->department;
                $employer->employers_posts_id = $this->post;
                $employer->employers_gender = $this->gender;
                
                if(!$employer->save()) {
                    Yii::$app->session->setFlash('profile-admin-error');
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $employer->getFirstErrors()));
                }
                
                $user = new User();
                $user->user_login = $this->username;
                $user->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $user->user_email = $this->email;
                $user->user_mobile = $this->mobile;
                $user->status = User::STATUS_ENABLED;

                if(!$user->save(false)) {
                    Yii::$app->session->setFlash('profile-admin-error');
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $user->getFirstErrors()));
                }
                
                
            }
            $transaction->commit();
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
    }
    
    public function attributeLabels() {
        return [
            'username' => 'Имя пользователя (Логин)',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
            'mobile' => 'Мобильный телефон',
            'email' => 'Электронный адрес',
            'photo' => 'Фотография',
            'role' => 'Роль',
            'rule' => 'Добавление новостей',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'second_name' => 'Отчество',
            'gender' => 'Пол',
            'department' => 'Подразделение',
            'post' => 'Должность',
            'is_new' => 'Добавлять новости',
        ];
    }
    
    

}
