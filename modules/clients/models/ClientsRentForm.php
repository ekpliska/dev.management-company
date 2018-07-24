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
    const SCENARIO_NOT_REQUIRED = 'not required fields';
    
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
            
            [
                'rents_mobile', 'unique',
                'targetClass' => Clients::className(),
                'targetAttribute' => 'clients_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже использовается в системе',
                'on' => self::SCENARIO_AJAX_VALIDATION,
            ],
            
            ['password', 'string', 'min' => 6, 'max' => 12, 'on' => self::SCENARIO_AJAX_VALIDATION],
            
            ['rents_email', 'email', 'on' => self::SCENARIO_AJAX_VALIDATION],
        ];
    }
    
    /*
     * Добавление арендатора прикрепленного к заданному собственнику
     * Для арендатора создаем новую учетную запись
     * Новому арендатору присваиваем статус - Активнй
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
    
    public function saveRentToUser($data) {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        if ($data) {
            try {
                $add_rent = new Rents();
                $add_rent->rents_name = $this->rents_name;
                $add_rent->rents_second_name = $this->rents_second_name;
                $add_rent->rents_surname = $this->rents_surname;
                $add_rent->rents_mobile = $this->rents_mobile;
                // Связать с собственником
                $add_rent->isActive = Rents::STATUS_ENABLED;

                if(!$add_rent->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения арендатора. Ошибка: ' . join(', ', $add_rent->getFirstErrors()));
                }
                
                $add_user = new User();
                $add_user->user_login = 'r';
                $add_user->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $add_user->user_email = $this->rents_email;
                $add_user->user_mobile = $this->rents_mobile;
                $add_user->status = User::STATUS_ENABLED;
                $add_user->save();
                
                if (!$add_user->save()) {
                    throw new \yii\db\Exception('Ошибка сохранения пользователя. Ошибка: ' . join(', ', $add_user->getFirstErrors()));
                }
                
                $transaction->commit();
                
            } catch (Exception $ex) {
                $transaction->rollBack();
                // $ex->getMessage();
            }
            return true;
        }
        return fale;
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
