<?php

    namespace app\components\clientapi;
    use yii\base\Object;

/**
 * API для реализации 
 *      регистрации новых пользователей, 
 *      добавление нового лицевого счета,
 */
class ClientAPI extends Object {
    
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
        
        $url = 'https://api.myelsa.ru/api/' . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
//        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
//        curl_setopt($c, CURLOPT_TIMEOUT, 30);
//        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($ch);

        return json_decode($result, true);        
        
    }
    
    
}
