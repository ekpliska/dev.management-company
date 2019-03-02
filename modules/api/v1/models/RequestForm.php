<?php

    namespace app\modules\api\v1\models;
    use Yii;
    use yii\base\Model;
    use app\models\TypeRequests;
    use app\models\Requests;
    use app\models\PersonalAccount;

/**
 * Добавление заявки
 */
class RequestForm extends Model {
    
    public $account;
    public $type_request;
    public $request_body;
    public $gallery = [];

    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['account', 'type_request', 'request_body'], 'required'],
            ['account', 'integer'],
            ['type_request', 'string', 'max' => 70],
            ['request_body', 'string', 'max' => 255],
            
        ];
    }
    
    /*
     * Сохранение созданной заявки
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $type_request_name = TypeRequests::find()
                    ->where(['=', 'type_requests_name', $this->type_request])
                    ->one();
            
            $account_info = PersonalAccount::findOne(['account_number' => $this->account]);
            
            if (empty($type_request_name) || empty($account_info)) {
                return [
                    'success' => false,
                    'message' => 'Ошибка формирования заявки',
                ];
            }
            
            $add_request = new Requests();
            
            $date = new \DateTime();
            $int = $date->getTimestamp();
            $request_numder = substr($int, 5) . '-' . str_pad($type_request_name->type_requests_id, 2, 0, STR_PAD_LEFT); 
            
            $add_request->requests_ident = $request_numder;
            $add_request->requests_type_id = $type_request_name->type_requests_id;
            $add_request->requests_comment = $this->request_body;
            $add_request->requests_phone = $this->rents_mobile;

            if(!$add_request->save()) {
                return [
                    'success' => false,
                    'message' => 'Ошибка формирования заявки',
                ];
            }
                
            $transaction->commit();
            
            return $add_request->requests_ident;
                
        } catch (Exception $ex) {
            $transaction->rollBack();
            // $ex->getMessage();
        }
        
        
    }
    
}
