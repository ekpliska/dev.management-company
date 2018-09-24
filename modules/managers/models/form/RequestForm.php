<?php

    namespace app\modules\managers\models\form;
    use Yii;
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
            
            $date = new \DateTime();
            $int = $date->getTimestamp();

            $numder = substr($int, 5) . '-' . str_pad($this->category_service, 2, 0, STR_PAD_LEFT) . '-' . str_pad($this->service_name, 2, 0, STR_PAD_LEFT);
            $application->applications_number = $numder;
            $application->applications_category_id = $this->category_service;
            $application->applications_service_id = $this->service_name;
            $application->applications_phone = $this->phone;
            $application->status = StatusRequest::STATUS_NEW;
            
            $application->save();
            
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
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
