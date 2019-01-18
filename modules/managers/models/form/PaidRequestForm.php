<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\PaidServices;
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\StatusRequest;

/**
 * Заявка на платную услугу
 */
class PaidRequestForm extends Model {
   
    public $servise_category;
    public $servise_name;
    public $phone;
    public $flat;
    public $description;
    
    /*
     *  Правила валидации
     */
    public function rules() {
        return [
            [['servise_category', 'servise_name', 'phone', 'description', 'flat'], 'required'],
            
            ['description', 'string', 'min' => 10, 'max' => 255],
            
            ['phone', 'existenceClient'],
            ['phone',
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
            ],
        ];
    }
    
    /*
     * Проверяет наличие собственника или арендатора по указанному номеру телефона
     */
    public function existenceClient() {
        
        $user = User::find()
                ->andWhere(['user_mobile' => $this->phone])
                ->one();
        
        $client = $user['user_client_id'];
        $rent = $user['user_rent_id'];
        
        if ($client == null && $rent == null) {
            $errorMsg = 'Указанный номер мобтльного телефона в системе не зарегистрирован';
            $this->addError('phone', $errorMsg);
        }

    }
    
    public function save() {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $paid_request = new PaidServices();

            /* Формирование идентификатора для заявки:
             *      последние 6 символов даты в unix - 
             *      код вида платной заявки
             */
            
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $numder = substr($int, 5) . '-' . str_pad($this->servise_category, 2, 0, STR_PAD_LEFT);
            
            $paid_request->services_number = $numder;
            $paid_request->services_servise_category_id = $this->servise_category;
            $paid_request->services_name_services_id = $this->servise_name;
            $paid_request->services_phone = $this->phone;
            $paid_request->services_comment = $this->description;
            $paid_request->status = StatusRequest::STATUS_NEW;
            $paid_request->services_account_id = $this->flat;
            
            $paid_request->save();
            
            $transaction->commit();
            
            return $numder;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'servise_category' => 'Вид услуги',
            'servise_name' => 'Наименование услуги',
            'phone' => 'Мобильный телефон',
            'flat' => 'Адрес',
            'description' => 'Описание заявки',
        ];
    }
    
}
