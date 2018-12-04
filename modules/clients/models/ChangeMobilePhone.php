<?php

    namespace app\modules\clients\models;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\SmsOperations;

/**
 * Смена номера мобильного телефона
 * 
 * @param string $new_phone Новый пароль
 */
class ChangeMobilePhone extends Model {
    
    public $new_phone;
    
    private $_user;
    
    public function __construct(User $user, $config = []) {
        
        $this->_user = $user;
        parent::__construct($config);
        
    }
    
    /*
     * Правила валидации
     */
    public function rules() {
        
        return [
            [['new_phone'], 'required'],

            ['new_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', 
            ],
            
            [
                'new_phone', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер мобильного телефона используется',
            ],            
            
        ];        
    }
        
    /*
     * Первый этап смены номера телефона
     * После прохождения валидации создаем запись в таблице "СМС операции"
     * Присваиваем операции статус "Смена номера телефона"
     */
    public function checkPhone() {
        
        if ($this->validate()) {
            $sms_model = new SmsOperations();
            $sms_model->operations_type = SmsOperations::TYPE_CHANGE_PHONE;
            $sms_model->user_id = Yii::$app->user->identity->id;
            $sms_model->sms_code = mt_rand(10000, 99999);
            $sms_model->date_registration = time();
            $sms_model->value = $this->new_phone;
            $sms_model->save(false);
            return true;
        }
        return false;
    }
    
    /*
     * Метки для полей
     */
    public function attributeLabels() {
        return [
            'new_phone' => 'Мобильный телефон',
        ];
    }
    
    
    
}
