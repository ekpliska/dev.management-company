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
    
    public function addRequest($username) {
        
        $account = PersonalAccount::findByAccountNumber($username);
        var_dump($account);die;
        
        $new_requests = new Requests();
        if ($new_requests->validate()) {
            $new_requests->requests_ident = 'test' . '-' . str_pad($this->request_type, 2, 0, STR_PAD_LEFT);
            $new_requests->requests_type_id = $this->request_type;
            $new_requests->requests_comment = $this->request_comment;
            $new_requests->requests_user_id = Yii::$app->user->identity->user_id;
            $new_requests->status = Requests::STATUS_IN_WORK;
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
