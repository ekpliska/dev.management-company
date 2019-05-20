<?php

    namespace app\modules\api\v1\models\payments;
    use Yii;
    use yii\base\Model;
    use app\models\Payments;
    use app\models\PersonalAccount;
/*
 * Проведение платежа
 */
class PaymentForm extends Model {
    
    public $account_number;
    public $receipt_period;
    public $receipt_num;
    public $receipt_sum;
    public $client_name;
    public $cryptogram_packet;
    
    public function rules() {
        return [
            [['account_number', 'receipt_period', 'receipt_num', 'receipt_sum', 'client_name', 'cryptogram_packet'], 'required'],
            [['receipt_period', 'receipt_num'], 'string', 'min' => 3, 'max' => 20],
            
            ['account_number', 'integer'],
            
            [['receipt_sum'], 'number'],
            ['receipt_sum', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            
            [['client_name', 'cryptogram_packet'], 'string', 'min' => 5],
        ];
    }
    
    /*
     * Проведение платежа
     */
    public function sendPayment() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $account_info = PersonalAccount::findOne(['account_number' => $this->account_number]);
        if ($account_info == null) {
            return ['success' => false];
        }
        
        /*
         * По прищедщим данным проверяем существование платежа
         * Есди платеж не существуем, создаем его
         */
        $_period = $this->receipt_period;
        $_nubmer = $this->receipt_num;
        $_sum = $this->receipt_sum;
        $accoint_id = $account_info->account_id;
        
        $is_payment = Payments::isPayment($_period, $_nubmer, $_sum, $accoint_id);
        $payment_number = $is_payment['payment']->unique_number;
        
        // Если платеж был оплачен ранее
        if ($is_payment['status'] == Payments::YES_PAID) {
            return ['success' => true, 'message' => "Оплата квитанции {$this->receipt_num} была совершена ранее"];
        } elseif ($is_payment['status'] == Payments::NOT_PAID) {
            // Собираем данные для отправки по платежному шлюзу
            $data_posts = [
                'Amount' => $is_payment['payment']->payment_sum,
                'Currency' => 'RUB',
                'InvoiceId' => $is_payment['payment']->unique_number,
                'Description' => Yii::$app->paymentSystem->description,
                'AccountId' => $account_info->account_id,
                'Name' => $this->client_name,
                'CardCryptogramPacket' => $this->cryptogram_packet
            ];
            
            if (!$result = Yii::$app->paymentSystem->send_payment($data_posts, $payment_number)) {
                return $result;
//                return ['success' => false, 'message' => $result];
            }
            return $result;
//            return ['success' => true, 'message' => $result];
        }
        
        
        
    }
    
}
