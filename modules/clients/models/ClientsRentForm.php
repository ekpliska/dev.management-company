<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use Yii;
    use yii\web\UploadedFile;
    use app\models\Clients;
    use app\models\User;
    use app\models\Rents;
    

/*
 * Модель для формы добавления арендатора
 */     
class ClientsRentForm extends Model {
    
    const SCENARIO_AJAX_VALIDATION = 'required fields';
    
    public $rents_surname;
    public $rents_name;
    public $rents_second_name;
    public $rents_mobile;
    public $rents_email;
    public $password;

    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['rents_surname', 'rents_name', 'rents_second_name', 'rents_mobile', 'rents_email', 'password'], 'required', 'on' => self::SCENARIO_AJAX_VALIDATION],
            [['rents_surname', 'rents_name', 'rents_second_name'], 'filter', 'filter' => 'trim', 'on' => self::SCENARIO_AJAX_VALIDATION],
            [['rents_surname', 'rents_name', 'rents_second_name'], 'string', 'min' => 3, 'max' => 50, 'on' => self::SCENARIO_AJAX_VALIDATION],
            [
                ['rents_surname', 'rents_name', 'rents_second_name'], 
                'match', 
                'pattern' => '/^[А-Яа-я\ \-]+$/iu', 
                'on' => self::SCENARIO_AJAX_VALIDATION,
                'message' => 'Для заполнения поля "{attribute}" используйте только буквы русского алфавита, и знак тире'
            ],
            
            [
                'rents_mobile', 'unique',
                'targetClass' => Clients::className(),
                'targetAttribute' => 'clients_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже используется в системе',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            ['rents_email', 'string', 'min' => 5, 'max' => 150, 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            ['password', 'string', 'min' => 6, 'max' => 12, 'on' => self::SCENARIO_AJAX_VALIDATION],
            ['password', 
                'match', 
                'pattern' => '/^[A-Za-z0-9\_\-]+$/iu', 
                'message' => 'Для задания пароля используйте буквы английского алфавита, цифры, знак тире и нижнее подчеркивание'],
            
            ['rents_email', 'email', 'on' => self::SCENARIO_AJAX_VALIDATION],
        ];
    }
    
    /*
     * Добавление арендатора прикрепленного к заданному собственнику
     * Для арендатора создаем новую учетную запись
     * Новому арендатору присваиваем статус - Активный
     */
    public function addNewClient($client_id) {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            if ($this->validate()) {
                $rent_new = new Rents();
                $rent_new->rents_name = $this->rents_name;
                $rent_new->rents_second_name = $this->rents_second_name;
                $rent_new->rents_surname = $this->rents_surname;
                $rent_new->rents_mobile = $this->rents_mobile;
                $rent_new->setAccountId(Yii::$app->user->identity->user_login);
                $rent_new->rents_clients_id = $client_id;
                $rent_new->rents_email = $this->rents_email;
                $rent_new->isActive = true;
                $rent_new->save();
                
                $user_new = new User();
                $user_new->user_login = Yii::$app->user->identity->user_login . '_rent';
                $user_new->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $user_new->user_mobile = $this->rents_mobile;
                $user_new->user_email = $this->rents_email;
                $user_new->status = User::STATUS_ENABLED;
                $user_new->user_rent_id = $rent_new->id;
                $user_new->save();
                
                $transaction->commit();
            }            
        } catch (Exception $e) {
            $transaction->rollBack();                
        }
    }
    
    /*
     * Метод сохранения нового арендатора через форму "Добавить лицевой счет"
     * Для арендатора создаем новую учетную запись
     * Новому арендатору присваиваем статус - Активный
     */
    public function saveRentToUser($data, $new_account) {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        if ($data) {
            try {
                
                $client = Clients::find()
                        ->andWhere(['clients_id' => Yii::$app->user->identity->user_client_id])
                        ->one();
                
                $add_rent = new Rents();
                $add_rent->rents_name = $this->rents_name;
                $add_rent->rents_second_name = $this->rents_second_name;
                $add_rent->rents_surname = $this->rents_surname;
                $add_rent->rents_mobile = $this->rents_mobile;
                // Записываем связь Арендатора с Собственником
                $add_rent->link('client', $client);
                $add_rent->isActive = Rents::STATUS_ENABLED;

                if(!$add_rent->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $add_rent->getFirstErrors()));
                }
                
                $add_user = new User();
                $add_user->user_login = $new_account . 'r';
                $add_user->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $add_user->user_email = $this->rents_email;
                $add_user->user_mobile = $this->rents_mobile;
                $add_user->status = User::STATUS_ENABLED;
                // Записываем связь Пользователя с Арендатором
                $add_user->link('rent', $add_rent);
                $add_user->save();
                
                if (!$add_user->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения пользователя. Ошибка: ' . join(', ', $add_user->getFirstErrors()));
                }
                
//                $data_bind = new \app\models\AccountToUsers();
//                $data_bind->link('user', $add_user);
                
                $transaction->commit();
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                // $ex->getMessage();
            }
            return ['rent' => $add_rent->rents_id];
        }
        return false;
    }
        
    public function attributeLabels() {
        return [
            'rents_surname' => 'Фамилия арендатора',
            'rents_name' => 'Имя арендатора',
            'rents_second_name' => 'Отчество арендатора',
            'rents_mobile' => 'Контактный телефон арендатора',
            'rents_email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }
    
        
}
