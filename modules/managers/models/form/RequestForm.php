<?php

    namespace app\modules\managers\models\form;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use app\models\Applications;
    use app\models\Clients;
    use app\models\Rents;
    use yii\base\Model;
    use app\models\StatusRequest;

/**
 * Новая заявка
 */
class RequestForm extends Model {
    
    public $category_service;
    public $service_name;
    public $phone;
    public $fullname;
    public $description;
    
     /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['category_service', 'service_name', 'phone', 'fullname', 'description'], 'required'],
            
            ['phone', 'existenceClient'],
        ];
    }  
    
    /*
     * Проверяет наличие собственника или арендатора по указанному номеру телефона
     */
    public function existenceClient() {
        
        $client = Clients::find()
                ->andWhere(['clients_mobile' => $this->phone])
                ->orWhere(['clients_phone' => $this->phone])
                ->one();

        $rent = Rents::find()
                ->andwhere(['rents_mobile' => $this->phone])
                ->orWhere(['rents_mobile_more' => $this->phone])
                ->one();
        
        if ($client == null && $rent == null) {
            $errorMsg = 'Собственник или арендатор по указанному номеру мобильного телефона на найден. Укажите существующий номер телефона';
            $this->addError('phone', $errorMsg);
        }

    }
    
    public function save() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $application = new Applications();
            $client = $this->findClientPhone($this->phone);
            
            
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $numder = substr($int, 5) . '-' . str_pad($this->category_service, 2, 0, STR_PAD_LEFT) . '-' . str_pad($this->service_name, 2, 0, STR_PAD_LEFT);
            $application->applications_number = $numder;
            $application->applications_category_id = $this->category_service;
            $application->applications_service_id = $this->service_name;
            $application->applications_phone = $this->phone;
            $application->applications_description = $this->description;
            $application->status = StatusRequest::STATUS_NEW;
            $application->applications_account_id = $client['account_id'];
            
            $application->save();
            
            $transaction->commit();
            
            return $application->number;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
    }
    
    /*
     * Метод возвращает ID Собственника/Арендатора и его лицевой счет
     */
    public function findClientPhone($phone) {
        
        $client = (new \yii\db\Query)
                ->select('c.clients_id as id, p.account_id as account_id,'
                        . 'h.houses_id as house_id')
                ->from('clients as c')
                ->join('LEFT JOIN', 'personal_account as p', 'p.personal_clients_id = c.clients_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_client_id = c.clients_id')
                ->where(['c.clients_mobile' => $phone])
                ->orWhere(['c.clients_phone' => $phone])
                ->groupBy('h.houses_id')
                ->all();
        
        $rent = (new \yii\db\Query)
                ->select('r.rents_id as id, p.account_id as account_id')
                ->from('rents as r')
                ->join('LEFT JOIN', 'personal_account as p', 'p.personal_rent_id = r.rents_id')                
                ->where(['r.rents_mobile' => $phone])
                ->orWhere(['r.rents_mobile_more' => $phone])
                ->one();
        
        if ($client == null && $rent == null) {
            return false;
        } elseif ($client != null && $rent == null) {
            return \yii\helpers\ArrayHelper::getColumn($client, 'house_id');
        } elseif ($client == null && $rent != null) {
            return [
                'id' => $rent['id'], 
                'account_id' => $rent['account_id']];
        }
                
    }
    
    public function attributeLabels() {
        return [
            'category_service' => 'Вид услуги',
            'service_name' => 'Название услуги',
            'phone' => 'Мобильный телефон',
            'fullname' => 'Фамилия имя отчество',
            'description' => 'Описание',
        ];
    }
}
