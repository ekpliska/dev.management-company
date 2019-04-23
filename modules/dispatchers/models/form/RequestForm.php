<?php

    namespace app\modules\dispatchers\models\form;
    use Yii;
    use app\models\Requests;
    use app\models\User;
    use yii\base\Model;
    use app\models\StatusRequest;

/**
 * Новая заявка
 */
class RequestForm extends Model {
    
    public $type_request;
    public $phone;
    public $flat;
    public $description;
    
     /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['type_request', 'phone', 'description', 'flat'], 'required'],
            
            ['description', 'string', 'max' => 1000],
            
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
            $errorMsg = 'Указанный номер мобильного телефона в системе не зарегистрирован';
            $this->addError('phone', $errorMsg);
        }

    }
    
    /*
     * Метод сохранения заявки
     */
    public function save() {
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $request = new Requests();

            /* Формирование идентификатора для заявки:
             *      последние 6 символов даты в unix - 
             *      тип заявки
             */
        
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $request_numder = substr($int, 5) . '-' . str_pad($this->type_request, 2, 0, STR_PAD_LEFT);      

            $numder = $request_numder;
            $request->requests_ident = $numder;
            $request->requests_type_id = $this->type_request;
            $request->requests_phone = $this->phone;
            $request->requests_comment = $this->description;
            $request->status = StatusRequest::STATUS_IN_WORK;
            $request->is_accept = 1;
            $request->requests_account_id = $this->flat;
            $request->requests_dispatcher_id = Yii::$app->profileDispatcher->employeeID;
            
            $request->save();
            
            $transaction->commit();
            
            return $request->requests_ident;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
    }
    
    /*
     * Метод возвращает ID Собственника/Арендатора и его лицевой счет
     */
    public function findClientPhone($phone) {
        
        $user = User::find()
                ->where(['LIKE', new \yii\db\Expression('REPLACE(user_mobile, " ", "")'), $phone])
                ->asArray()
                ->one();
        
        $client_id = $user['user_client_id'] ? $user['user_client_id'] : $user['user_rent_id'];
        return $client_id;
        
    }
    
    public function attributeLabels() {
        return [
            'type_request' => 'Вид заявки',
            'phone' => 'Мобильный телефон',
            'flat' => 'Дом',
            'fullname' => 'Фамилия имя отчество',
            'description' => 'Описание',
        ];
    }
}
