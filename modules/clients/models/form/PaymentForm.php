<?php

    namespace app\modules\clients\models\form;
    use Yii;
    use yii\base\Model;

/**
 * Форма оплаты квитанции
 * 
 * @param string $username Логин магазина, полученный при подключении
 * @param string $password Пароль магазина, полученный при подключении
 * @param integer $orderId Номер заказа в платежной системе. Уникален в пределах системы
 * @param integer $amount Сумма списания в валюте заказа. Может быть меньше или равна сумме преавторизации. Не может быть меньше 1 рубля
 */
class PaymentForm extends Model {
    
    public $username;
    public $password;
    public $order_id;
    public $amount;
    
//    public function __construct() {
//        
//    }
    
    /*
     *  Правила валидации
     */
    public function rules() {
        
        return [
            [['order_id', 'amount'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['order_id', 'integer'],
            ['amount', 'checkAmount'],
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
            'order_id' => 'Номер заказа (?)',
            'amount' => 'Сумма к оплате',
        ];
        
    }
    
}
