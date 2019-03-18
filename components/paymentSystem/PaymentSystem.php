<?php

    namespace app\components\paymentSystem;
    use Yii;

/*
 * Интеграция с платежной системой РайффайзенБанк
 * по REST
 */
class PaymentSystem {
        
    /**
     * ДАННЫЕ ДЛЯ ПОДКЛЮЧЕНИЯ К ПЛАТЕЖНОМУ ШЛЮЗУ
     *
     * username         Логин магазина, полученный при подключении.
     * password         Пароль магазина, полученный при подключении.
     * gateway_url      Адрес платежного шлюза.
     * gateway_url      Адрес, на который надо перенаправить пользователя
     *                  в случае успешной оплаты.
     * country_code     Код страны продавца ISO (должен быть установлен всегда 643)
     * currency_code    Код валюты сделки ISO (должен быть установлен всегда 643)
     * merchant_name    Имя магазина (не более 25 символов)
     * merchant_url     URL сервера магазина
     * merchant_city    Город магазина (большими буквами на английском языке)
     * merchant_id      Идентификатор магазина, передается в формате
     *                  00000NNNNNNNNNN-NNNNNNNN
     *                  (00000MerchantID-TerminalID)
     * success_url      URL ресурса, куда будет перенаправлен клиент 
     *                  в случае успешного платежа
     * fail_url         URL ресурса, куда будет перенаправлен клиент 
     *                  в случае неуспешного платежа
     * 
     */
    
    public $username;
    public $password;
    public $gateway_url;
    public $country_code = 643;
    public $currency_code = 643;
    public $merchant_name = '';
    public $merchant_url = '';
    public $merchant_city = 'SAINT PETERSBURG';
    public $merchant_id = '';
    public $success_url = '';
    public $fail_url = '';

    /**
    * ФУНКЦИЯ ДЛЯ ВЗАИМОДЕЙСТВИЯ С ПЛАТЕЖНЫМ ШЛЮЗОМ
    *
    * Для отправки POST запросов на платежный шлюз используется
    * стандартная библиотека cURL.
    *
    * ПАРАМЕТРЫ
    *      url         URL-адреса куда уходит форма.
    *      data        Массив данных.
    *
    * ОТВЕТ
    *      response    Ответ.
    */
   private function gateway($url, $data) {
       
        $curl = curl_init(); // Инициализируем запрос
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $method, // Полный адрес метода
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => true, // Метод POST
            CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
        ));
        $response = curl_exec($curl); // Выполняем запрос

        $response = json_decode($response, true); // Декодируем из JSON в массив
        curl_close($curl); // Закрываем соединение
        return $response; // Возвращаем ответ
        
    }
    
    /*
     * Отправка платежа
     */
    public function send_payment($method, $post_data) {
        var_dump($post_data); die();
        $data = [
            'userName' => $this->username,
            'password' => $this->password,
            'PurchaseAmt' => $post_data['PurchaseAmt'],
            'PurchaseDesc' => $post_data['PurchaseDesc'],
            'CountryCode' => $this->country_code,
            'CurrencyCode' => $this->currency_code,
            'MerchantName' => $this->merchant_name,
            'MerchantURL' => $this->merchant_url
        ];
        
        /**
        * ЗАПРОС РЕГИСТРАЦИИ ПЛАТЕЖА В ПЛАТЕЖНОМ ШЛЮЗЕ
        */
        
        $response = $this->gateway('registerPreAuth.do', $data);
        
        // В случае ошибки соверщение платежа, перенаправление на страницу ошибки
        if (isset($response['errorCode'])) {
            return Yii::$app->response->redirect($this->fail_url);
//            return ['error' => "Ошибка #{$response['errorCode']} : {$response['errorMessage']}"];
        } else { // В случае успеха перенаправить пользователя на платежную форму
            header('Location: ' . $response['formUrl']);
            die();
        }
        
    }
    
    /**
     * ЗАПРОС СОСТОЯНИЯ ЗАКАЗА
     */
    public static function get_payment_status() {
        
    }
    
}
