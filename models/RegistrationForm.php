<?php

    namespace app\models;
    use yii\base\Model;
    use app\models\PersonalAccount;

/**
 * Форма регистрации пользователя
 */
class RegistrationForm extends Model {

    public $username;
    public $password;
    public $password_repeat;
    public $last_sum;
    public $square;
    public $mobile_phone;
    public $email;
    public $verifyCode;
    
    private $_user = false;
    
    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'username', 'password',
                'last_sum', 'square',
                'mobile_phone', 'email',
                'password_repeat'], 'required'
            ],
            
            [[
                'username', 
                'last_sum', 'square',
                'mobile_phone', 'email'], 'filter', 'filter' => 'trim'],
            
            [['username'], 'string', 'min' => 6, 'max' => 70],
            
            // Проверка введенного логина на уникалность в таблице Пользователи
            [
                'username', 'unique', 
                'targetClass' => User::className(), 
                'targetAttribute' => 'user_login', 
                'message' => 'Пользователь с введенным лицевым счетом в системе уже зарегистрирован'
            ],
            
            ['username', 'checkPersonalAccount'],
            
            ['password', 'compare', 'message' => 'Указанные пароли не совпадают!'],            
            [['password_repeat'], 'string', 'min' => 6, 'max' => 12],
            
            ['email', 'email'],
            
        ];
    }
    
    /*
     * Проверка валидации введенного лицевого счета при регистрации
     * Если по введенному лицевому счету запись на найдена - выбросить исключение
     */
    public function checkPersonalAccount() {
        $personalAccount = PersonalAccount::find()->andWhere(['account_number' => $this->username])->select('account_number')->one();
        if ($personalAccount == null) {
            $errorMsg = "Указанный лицевой счет не существует";
            $this->addError('username', $errorMsg);
        }
    }
    
    
    /*
     * Метки для полей формы
     */
    public function attributeLabels() {
        return [
            'username' => 'Номер лицевого счета',
            'last_sum' => 'Сумма предыдущей квитации',
            'square' => 'Площадь квартиры',
            'mobile_phone' => 'Мобильный телефон',
            'email' => 'Электронная почта',
            'password_repeat' => 'Пароль',
            'password' => 'Повторите введенный пароль',
        ];
    }

}
