<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\Payments;
    use app\components\paymentSystem\PaymentSystem;

/**
 * Форма оплаты квитанции
 * 
 * @param string $unique_number Уникальный идентификатор платежа
 * @param integer $receipt_period Расчетный период
 * @param string $receipt_number Номер квитанции
 * @param decimal $payment_sum Сумма к оплате
 */
class PaymentForm extends Model {
    
    // Сумма платежа
    public $payment_sum;

    // Платеж
    private $_payment;

    public function __construct(Payments $payment, $config = []) {
        $this->_payment = $payment;
        parent::__construct();
        
    }
    
    /*
     *  Правила валидации
     */
    public function rules() {
        
        return [
            [['payment_sum'], 'required', 'message' => 'Укажите сумму платежа'],
            ['payment_sum', 'checkAmount', 'skipOnEmpty'=> false],
        ];
        
    }
    
    /*
     * Валидация 
     */
    public function checkAmount() {
        if ($this->payment_sum < 1) {
            $this->addError('payment_sum', 'Указана некорректная сумма');
        }
    }
    
    /*
     * Отправка платежа
     */
    public function send() {
        
        if (!$this->validate()) {
            return false;
        }
        
        /*
         * PurchaseAmt     Сумма оплаты
         * PurchaseDesc    Уникакльный номер заказа
         */
        $post_data = [
            'PurchaseAmt' => $this->payment_sum,
            'PurchaseDesc' => $this->_payment->unique_number,
        ];
        
        // Отправляем запрос на проведение оплаты платежа
        $send_payment = new PaymentSystem();
        $result = $send_payment->send_payment($method, $post_data);
        
        // Если при проведение платежа возникла ошибка, получаем ее код и пояснение
        if (isset($result['error'])) {
            return $result['error'];
        }
        
        return true;
        
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        
        return [
            'payment_sum' => 'Сумма платежа',
        ];
        
    }
    
}
