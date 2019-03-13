<?php

    namespace app\modules\api\v1\models\paidRequests;
    use Yii;
    use yii\base\Model;
    use yii\behaviors\TimestampBehavior;
    use app\models\PersonalAccount;
    use app\models\PaidServices;
    use app\models\StatusRequest;

/**
 * Создание заявки
 */
class PaidRequestForm extends Model {
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public $account;
    public $category_id;
    public $service_id;
    public $request_body;
    
    public function rules() {
        
        return [
            [['account', 'category_id', 'service_id', 'request_body'], 'required'],
            [['account', 'category_id', 'service_id'], 'integer'],
            [['request_body'], 'string', 'min' => 10, 'max' => 250],
        ];
        
    }
    
    /*
     * Сохранения заявки на платную услугу
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        // Проверка существования указанного лицевого счета
        $account_info = PersonalAccount::findOne(['account_number' => $this->account]);
        if (empty($account_info)) {
            return false;
        }
        
        $paid_request = new PaidServices();
        /*
         * Создание номера заявки
         */
        $date = new \DateTime();
        $int = $date->getTimestamp();
        $order_numder = substr($int, 5) . '-' . str_pad($this->service_id, 2, 0, STR_PAD_LEFT);
        
        $paid_request->services_number = $order_numder;
        $paid_request->services_servise_category_id = $this->category_id;
        $paid_request->services_name_services_id = $this->service_id;
        $paid_request->services_phone = Yii::$app->user->identity->user_mobile;
        $paid_request->services_comment = $this->request_body;
        $paid_request->status = StatusRequest::STATUS_NEW;
        $paid_request->services_account_id = $account_info->account_id;
        
        return $paid_request->save() ? true : false;
        
    }
    
}
