<?php
    
    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\Employees;

/**
 * Новый Диспетчер
 */

class EmployeeForm extends Model {
    
    // Данные пользователя
    public $username;
    public $password;
    public $password_repeat;
    public $mobile;
    public $email;
    public $photo;
    public $role;
    public $rule;
    
    // Данные сотрудника
    public $surname;
    public $name;
    public $second_name;
    public $birthday;
    public $department;
    public $post;
    
    // Разрешение на добавление новостей
    public $is_new = false;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            
            [[
                'username', 'password', 'password_repeat', 'mobile', 'email',
                'surname', 'name', 'second_name', 'birthday', 'department', 'post',
                ], 
                'required', 'message' => 'Поле "{attribute}" обязательно для заполнения'],
            
            ['role', 'string'],
            
            [['surname', 'name', 'second_name'], 'filter', 'filter' => 'trim'],
            [['surname', 'name', 'second_name'], 'string', 'min' => 3, 'max' => 50],
            [
                ['surname', 'name', 'second_name'], 
                'match', 
                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],            
            
            
            ['username', 'string', 'min' => 3, 'max' => 50],
            
            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 12],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Указанные пароли не совпадают'],
            
            [['username', 'password', 'password_repeat'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],            
            ['username', 'unique', 
                'targetClass' => User::className(), 
                'targetAttribute' => 'user_login', 
                'message' => 'Данное имя пользователя в систмеме уже используется'
            ],

            ['email', 'email'],
            ['email', 'match',
                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
            ],
            ['email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Указанный электронный адрес в системе уже используется'
            ],
            
            ['mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            ['mobile', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер мобильного телефона в системе уже используется'
            ],
            
            [['photo'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['photo'], 'image', 'maxWidth' => 510, 'maxHeight' => 510],
            
            
        ];
    }
    
    /*
     * Метод отвечает за добавление сведений о новом сотруднике и создание учетной записи для него
     */
    public function addEmployer($file, $role) {
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->validate()) {
                
                // Добавляем нового Сотрудника
                $employee = new Employees();
                $employee->employee_name = $this->name;
                $employee->employee_surname = $this->surname;
                $employee->employee_second_name = $this->second_name;
                $employee->employee_department_id = $this->department;
                $employee->employee_posts_id = $this->post;
                $employee->employee_birthday = $this->birthday;
                
                if(!$employee->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $employer->getFirstErrors()));
                }
                
                // Создаем нового пользователя для сотрудника
                $user = new User();
                $user->user_login = $this->username;
                $user->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $user->user_email = $this->email;
                $user->user_mobile = $this->mobile;
                $user->status = User::STATUS_ENABLED;
                // Загрузка фотографии
                $user->uploadPhoto($file);
                $user->link('employee', $employee);

                if(!$user->save(false)) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $user->getFirstErrors()));
                }
                
                // Назначение роли пользователю
                $user->setRole($role, $user->user_id);
                
                // Устанавливаем права на добавление новостей
                if ($this->is_new === 1) {
                    $permission_news = Yii::$app->authManager->getPermission('AddNews');
                    Yii::$app->authManager->assign($permission_news, $user->user_id);
                }
                
            }
            
            $transaction->commit();
            return $employee->id;
            
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
            'role' => 'Роль пользователя',
            'rule' => 'Добавление новостей',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'second_name' => 'Отчество',
            'birthday' => 'Дата рождения',
            'department' => 'Подразделение',
            'post' => 'Должность',
            'is_new' => 'Добавлять новости',
        ];
    }
    
    

}