<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;

/**
 * Форма оплаты квитанции
 * 
 * @param string $unique_number Уникальный идентификатор платежа
 * @param integer $receipt_period Расчетный период
 * @param string $receipt_number Номер квитанции
 * @param decimal $payment_sum Сумма к оплате
 */
class PaymentForm extends Model {
    
    public $receipt_period;
    public $receipt_number;
    public $payment_sum;
    
//    public function __construct() {
//        
//    }
    
    /*
     *  Правила валидации
     */
    public function rules() {
        
        return [
            [['payment_sum'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['payment_sum', 'checkAmount'],
        ];
        
    }
    
    /*
     * Валидация 
     */
    public function checkAmount($attribute, $params) {
        if ($this->attributes > 1) {
            $this->addError($attribute, 'Указана некорректная сумма');
        }
    }
    
    /*
     * Атрибуты полей
     */
    public function attributeLabels() {
        
        return [
            'payment_sum' => 'Сумма к оплате',
        ];
        
    }
    
}
