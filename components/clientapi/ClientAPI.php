<?php

    namespace app\components\clientapi;
    use yii\base\Object;
    use app\models\SiteSettings;

/**
 * API для реализации 
 *      регистрации новых пользователей, 
 *      добавление нового лицевого счета,
 */
class ClientAPI extends Object {
    
    public $url_api = 'https://api.myelsa.ru/api/';


    public function init() {
        
        parent::init();
        
        $_url = SiteSettings::find()
                ->indexBy('id')
                ->asArray()
                ->one();
        
        if (!empty($_url)) {
            $this->url_api = $_url['api_url'];
        }
        
    }
    
    /*
     * Регистрация нового пользователя
     * Добавление нового лицевого счета пользователя
     */
    public function accountRegister($data) {
        
        $account_info = $this->readUrl('account/register', $data);
        
        return $account_info;
        
    }    
    
    /*
     * Получение показаний приборов учета Собственника
     * за указанный период
     */
    public function getPreviousCounters($data) {
        
        $indications = $this->readUrl('counters/get', $data);
        
        if (isset($indications['status']) == 'error') {
            return false;
        }
        
        return $indications['Приборы учета'];
        
    }
    
    /*
     * Отправка текущих показаний приборов учета из личного кабинета Собственника
     */
    public function setCurrentIndications($data) {
        
        $new_indications = $this->readUrl('counters/set', $data);
        
        if (isset($new_indications['status']) == 'error') {
            return false;
        }
        
        return $new_indications['accepted'];
        
    }
    
    /*
     * Получение всех квитанций Собстевнника по лицевому счету,
     * Получение всех квитанций Собстевнника по лицевому счету за указанный период
     */
    public function getReceipts($data) {
        
        $receipts = $this->readUrl('receipt/get', $data);
        
        if (isset($receipts['status']) == 'error') {
            return false;
        }
        
        return $receipts['Квитанции ЖКУ'];
        
    }
    
    /*
     * Получение всех платежей Собстевнника по лицевому счету,
     * Получение всех платежей Собстевнника по лицевому счету за указанный период
     */
    public function getPayments($data) {
        
        $payments = $this->readUrl('payments/get', $data);
        
        if (isset($payments['success']) == 'error') {
            return false;
        }
        
        return $payments['Платежи'];
        
    }
    
    /*
     * Функция чтения URL
     */
    private function readUrl($path, $data) {
        
        $url = $this->url_api . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "charset=UTF-8"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $result = curl_exec($ch);
        
        return json_decode($result, true);        
        
    }
    
    
}
