<?php

    namespace app\components\paymentSystem;

/*
 * Интеграция с платежной системой АльфаБанка
 * по REST
 */
class PaymentSystem {
        
    /**
     * ДАННЫЕ ДЛЯ ПОДКЛЮЧЕНИЯ К ПЛАТЕЖНОМУ ШЛЮЗУ
     *
     * USERNAME         Логин магазина, полученный при подключении.
     * PASSWORD         Пароль магазина, полученный при подключении.
     * GATEWAY_URL      Адрес платежного шлюза.
     * RETURN_URL       Адрес, на который надо перенаправить пользователя
     *                  в случае успешной оплаты.
     */
    
    public $username;
    public $password;
    public $gateway_url;
    public $return_url;
    
    /**
    * ФУНКЦИЯ ДЛЯ ВЗАИМОДЕЙСТВИЯ С ПЛАТЕЖНЫМ ШЛЮЗОМ
    *
    * Для отправки POST запросов на платежный шлюз используется
    * стандартная библиотека cURL.
    *
    * ПАРАМЕТРЫ
    *      method      Метод из API.
    *      data        Массив данных.
    *
    * ОТВЕТ
    *      response    Ответ.
    */
    function gateway($method, $data) {
        $curl = curl_init(); // Инициализируем запрос
        curl_setopt_array($curl, array(
            CURLOPT_URL => GATEWAY_URL.$method, // Полный адрес метода
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => true, // Метод POST
            CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
        ));
        $response = curl_exec($curl); // Выполняем запрос

        $response = json_decode($response, true); // Декодируем из JSON в массив
        curl_close($curl); // Закрываем соединение
        return $response; // Возвращаем ответ
    }
    
}
