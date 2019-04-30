<?php

    namespace app\components\paymentSystem;
    use Yii;

/*
 * Интеграция с платежной системой Cloudpayments
 */
class PaymentSystem {
        
    /**
     * ДАННЫЕ ДЛЯ ПОДКЛЮЧЕНИЯ К ПЛАТЕЖНОМУ ШЛЮЗУ
     *
     * username         Логин магазина, полученный при подключении.
     * password         Пароль магазина, полученный при подключении.
     * gateway_url      Адрес платежного шлюза.
     * 
     */
    
    public $username;
    public $password;
    public $gateway_url = 'https://api.cloudpayments.ru/payments/cards/charge';

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
            CURLOPT_URL => $url, // Полный адрес метода
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
    public function send_payment($post_data) {
        
        $url = $this->gateway_url;
        $response = $this->gateway($url, $post_data);
        
        // В случае ошибки соверщение платежа, в ответе возвращаем код ошибки
        if ($response['Success'] == false) {
            return $response['Model']['ReasonCode'];
        } elseif ($response['Success'] == true) {
            return true;
        }
        
    }
    
}
