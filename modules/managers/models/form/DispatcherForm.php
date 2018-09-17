<?php
    
    namespace app\modules\managers\models\form;
    use yii\base\Model;
    use app\models\User;
    use app\models\Employers;

/**
 * Новый Диспетчер
 */
class DispatcherForm extends Model {
    
    // User info
    public $username;
    public $password;
    public $repeat_password;
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
    
    public function rules() {
        return [
            [[
                'username', 'password', 'repeat_password', 'mobile', 'photo', 'role', 'rule',
                'surname', 'name', 'second_name', 'gender', 'department', 'post'], 
                'required'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'username' => 'Имя пользователя (Логин)',
            'password' => 'Пароль',
            'repeat_password' => 'Пароль еще раз',
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
        ];
    }
    
    

}
