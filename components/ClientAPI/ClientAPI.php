<?php

    namespace app\components\ClientAPI;
    use yii\base\Object;

/**
 * API для реализации 
 *      регистрации новых пользователей, 
 *      добавление нового лицевого счета,
 */
class ClientAPI extends Object {
    
    /*
     * Внутренний метод, для регистрации
     * Вызов запроса и формирование URL
     */
    public function accountRegister($data) {
        
        $array = $this->readUrl('account/register', $data);
        return $array;
    }
    
    
    /*
     * Функция чтения URL
     */
    private function readUrl($path, $data) {
        
        $url = 'http://juox.ru/api/' . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        return json_decode($result, true);        
        
    }
    
    
}
