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
    
    public $surnamne;
    public $name;
    public $secondname;
    public $mobile;
    public $email;
    public $password;


    /*
     * Приавда валидации
     */
    public function rules() {
        return [
            [['surnamne', 'name', 'secondname', 'mobile', 'email', 'password'], 'required'],
            [['surnamne', 'name', 'secondname'], 'filter', 'filter' => 'trim'],
            
            [
                'mobile', 'unique',
                'targetClass' => Clients::className(),
                'targetAttribute' => 'clients_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
            ],
            
            ['password', 'string', 'min' => 6],
            
            ['email', 'email'],
        ];
    }
    
    /*
     * Добавление арендатора прикрепленного к заданному собственнику
     * Для арендатора создаем новую учетную запись
     */
    public function addNewClient() {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            if ($this->validate()) {
                $rent_new = new Rents();
                $rent_new->rents_name = $this->surnamne;
                $rent_new->rents_second_name = $this->name;
                $rent_new->rents_surname = $this->secondname;
                $rent_new->rents_mobile = $this->mobile;
                $rent_new->setAccountId(Yii::$app->user->identity->user_login);

                $user_new = new User();
                $user_new->user_login = Yii::$app->user->identity->user_login . '_rent';
                $user_new->user_password = Yii::$app->security->generatePasswordHash($this->password);
                $user_new->user_mobile = $this->mobile;
                $user_new->user_email = $this->email;
                $user_new->status = User::STATUS_ENABLED;
                $user_new->setUserAccountId(Yii::$app->user->identity->user_login);
                
                if ($rent_new->save() && $user_new->save()) {
                    $transaction->commit();
                }
            }            
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }
        
    public function attributeLabels() {
        return [
            'surnamne' => 'Фамилия арендатора',
            'name' => 'Имя арендатора',
            'secondname' => 'Отчество арендатора',
            'mobile' => 'Контактный телефон арендатора',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }
    
        
}
