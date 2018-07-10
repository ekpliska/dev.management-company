<?php

    namespace app\modules\clients\models;
    use yii\base\Model;
    use Yii;
    use app\models\Requests;
    use app\models\PersonalAccount;

/**
 * 
 */
class AddRequest extends Model {
    
    public $request_numder;
    public $request_type;
    public $request_phone;
    public $request_comment;
    
    public function rules() {
        return [
            
            ['request_numder', 'string'], 
            
            [['request_type', 'request_phone', 'request_comment'], 'required'],
            
            ['request_type', 'integer'],
            
            ['request_comment', 'string', 'min' => 10, 'max' => 1000],
            
            ['request_phone', 'string'],
        ];
    }
    
    public function addRequest($user) {
        
        $account = PersonalAccount::findByAccountNumber($user);
        
        /* Формирование идентификатора для заявки:
         * последние 4 символа лицевого счета - тип заявки
         */
        $this->request_numder = substr($account->account_number, 2) . '-' . str_pad($this->request_type, 2, 0, STR_PAD_LEFT);
        
        $new_requests = new Requests();
        if ($new_requests->validate()) {
            $new_requests->requests_ident = $this->request_numder;
            $new_requests->requests_type_id = $this->request_type;
            $new_requests->requests_comment = $this->request_comment;
            $new_requests->requests_phone = $this->request_phone;
            $new_requests->requests_user_id = $user;
            $new_requests->status = Requests::STATUS_NEW;
            $new_requests->is_accept = false;
            if ($new_requests->save()) {
                return true;
            }
        }
        
    }
    
    public function attributeLabels() {
        return [
            'request_numder' => 'Номер заявки (Идентификатор)',
            'request_type' => 'Вид заявки',
            'request_phone' => 'Телефон',
            'request_comment' => 'Описание',
        ];
    }
}
