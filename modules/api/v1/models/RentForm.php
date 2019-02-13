<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\PersonalAccount;
    use app\models\Rents;

/**
 * Добавление арендатора
 */
class RentForm extends Model {
    
    public $rents_surname;
    public $rents_name;
    public $rents_second_name;
    public $rents_mobile;
    public $rents_other_mobile;
    public $rents_email;
    public $password;
    public $password_repeat;
    
    public $client_id;
    public $account_number;


    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [[
                'rents_surname', 'rents_name', 'rents_second_name', 
                'rents_mobile', 
                'rents_email', 
                'client_id', 'account_number',
                'password', 'password_repeat'], 'required'],
            
//            [['rents_surname', 'rents_name', 'rents_second_name'], 'filter', 'filter' => 'trim'],
//            
//            [['rents_surname', 'rents_name', 'rents_second_name'], 'string', 'min' => 3, 'max' => 50],
//            
//            [
//                ['rents_surname', 'rents_name', 'rents_second_name'], 
//                'match', 
//                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
//                'on' => self::SCENARIO_AJAX_VALIDATION,
//                'message' => 'Поле может содержать только буквы русского алфавита, и знак "-"',
//            ],
//            
//            [
//                'rents_mobile', 'unique',
//                'targetClass' => User::className(),
//                'targetAttribute' => 'user_mobile',
//                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
//            ],
//            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
//            
//            [
//                'rents_email', 'unique',
//                'targetClass' => User::className(),
//                'targetAttribute' => 'user_email',
//                'message' => 'Данный электронный адрес уже используется в системе',
//            ],
//            ['rents_email', 'string', 'min' => 5, 'max' => 150],
//            ['rents_email', 'email'],
//            
//            ['rents_email', 'match',
//                'pattern' => '/^[A-Za-z0-9\_\-\@\.]+$/iu',
//                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
//            ],            
//            
//            [['password', 'password_repeat'], 'string', 'min' => 6, 'max' => 12],
//            [['password', 'password_repeat'],
//                'match', 
//                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
//                'message' => 'Поле "{attribute}" может содержать только буквы английского алфавита, цифры, знаки "-", "_"',
//            ],
//            
//            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Указанные пароли не совпадают!'],
            
        ];
    }
    
    /*
     * Метод сохранения нового арендатора через форму "Добавить лицевой счет"
     * Для арендатора создаем новую учетную запись
     * Новому арендатору присваиваем статус - Активный
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $account_info = PersonalAccount::findOne(['account_number' => $this->account_number]);
            if (empty($account_info)) {
                return false;
            }
            
            $add_rent = new Rents();
            $add_rent->rents_name = $this->rents_name;
            $add_rent->rents_second_name = $this->rents_second_name;
            $add_rent->rents_surname = $this->rents_surname;
            $add_rent->rents_mobile = $this->rents_mobile;
            $add_rent->rents_clients_id = $this->client_id;
            $add_rent->isActive = Rents::STATUS_ENABLED;

            if(!$add_rent->save()) {
                return false;
            }
                
            $add_user = new User();
            $add_user->user_login = $account_info->account_number . 'r';
            $add_user->user_password = Yii::$app->security->generatePasswordHash($this->password);
            $add_user->user_email = $this->rents_email;
            $add_user->user_mobile = $this->rents_mobile;
            $add_user->status = User::STATUS_ENABLED;
            $add_user->user_rent_id = $add_rent->rents_id;
                
            if (!$add_user->save()) {
                return false;
            }
            
            // Назначаем роль созданному арендатору
            $add_user->setRole('clients_rent', $add_user->id);

            // Связываем лицевой счет с созданным арендатором
            $account_info->link('rent', $add_rent);

            $transaction->commit();
            
            return $add_rent->rents_id;
                
        } catch (Exception $ex) {
            $transaction->rollBack();
            // $ex->getMessage();
        }
        
        
    }
    
}
