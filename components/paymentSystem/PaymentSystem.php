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
   private function gateway($method, $data) {
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
    
    /*
     * Отправка платежа
     */
    public function send_payment($method, $post_data) {
        
        $data = [
            'userName' => $this->username,
            'password' => $this->password,
            'orderNumber' => $post_data['orderNumber'],
            'amount' => urlencode($post_data['amount']),
            'returnUrl' => $this->return_url,
        ];
        
        /**
        * ЗАПРОС РЕГИСТРАЦИИ ДВУХСТАДИЙНОГО ПЛАТЕЖА В ПЛАТЕЖНОМ ШЛЮЗЕ
        *      registerPreAuth.do
        *
        * Параметры и ответ точно такие же, как и в предыдущем методе.
        * Необходимо вызывать либо register.do, либо registerPreAuth.do.
        */
        
        $response = $this->gateway('registerPreAuth.do', $data);
        
        if (isset($response['errorCode'])) { // В случае ошибки вывести ее
            return [
                'error' => "Ошибка #{$response['errorCode']} : {$response['errorMessage']}"
            ];
        } else { // В случае успеха перенаправить пользователя на платежную форму
            header('Location: ' . $response['formUrl']);
            die();
        }
        
    }
    
    /**
     * ЗАПРОС СОСТОЯНИЯ ЗАКАЗА
     *      getOrderStatus.do
     *
     * ПАРАМЕТРЫ
     *      userName        Логин магазина.
     *      password        Пароль магазина.
     *      orderId         Номер заказа в платежной системе. Уникален в пределах системы.
     *
     * ОТВЕТ
     *      ErrorCode       Код ошибки. Список возможных значений приведен в таблице ниже.
     *      OrderStatus     По значению этого параметра определяется состояние заказа в платежной системе.
     *                      Список возможных значений приведен в таблице ниже. Отсутствует, если заказ не был найден.
     *
     *  Код ошибки      Описание
     *      0           Обработка запроса прошла без системных ошибок.
     *      2           Заказ отклонен по причине ошибки в реквизитах платежа.
     *      5           Доступ запрещён;
     *                  Пользователь должен сменить свой пароль;
     *                  Номер заказа не указан.
     *      6           Неизвестный номер заказа.
     *      7           Системная ошибка.
     *
     *  Статус заказа   Описание
     *      0           Заказ зарегистрирован, но не оплачен.
     *      1           Предавторизованная сумма захолдирована (для двухстадийных платежей).
     *      2           Проведена полная авторизация суммы заказа.
     *      3           Авторизация отменена.
     *      4           По транзакции была проведена операция возврата.
     *      5           Инициирована авторизация через ACS банка-эмитента.
     *      6           Авторизация отклонена.
     */
    public static function get_payment_status($method, $post_data) {
        
        $data = [
            'userName' => $this->username,
            'password' => $this->password,
            'orderNumber' => $post_data['orderNumber'],
        ];
        
        $response = gateway('getOrderStatus.do', $data);
     
        // Вывод кода ошибки и статус заказа
        return [
            'Error code' => "Error code: {$response['ErrorCode']}",
            'Order status' => "Order status: {$response['OrderStatus']}",
        ];
    }
    
}
