<?php

    namespace app\components\paymentSystem;
    use Yii;
    use app\models\Payments;

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
    
    public $public_id = 'pk_1f5d5ad761f44549ac761e0329e86';
    public $api_secret = '979331b40c62fb869c8a10b377bf42ed';
    // Метод для оплаты по криптограмме
    public $gateway_url = 'https://api.cloudpayments.ru/payments/cards/charge';
    // Метод для проведение платежа
    public $post3ds_url = 'https://api.cloudpayments.ru/payments/cards/post3ds';

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
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "{$this->public_id}:{$this->api_secret}",
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
     *      @param array $post_data          Данные для отправки в платежный шлюз
     *      @param string $payment_number    Уникальный идентификатор платежа
     *                                       Если была использоватена карта без 3-D Secure устанавливаем платежу статус Оплачен
     */
    public function send_payment($post_data, $payment_number) {
        
        $url = $this->gateway_url;
        $response = $this->gateway($url, $post_data);
        
        // Если карта с 3-D Secure 
        if ($response['Success'] == false) {
            return isset($response['Model']) ? $response['Model'] : $response['Model']['CardHolderMessage'];
        } elseif ($response['Success'] == true) {
            // Если карта без 3-D Secure устанавливаем платежу статус Оплачен
            $payment = Payments::findOne(['unique_number' => $payment_number]);
            if ($payment) {
                $payment->changeStatus();
            }
            return isset($response['Model']['CardHolderMessage']) ? $response['Model']['CardHolderMessage'] : $response['Model'];
        }
        
    }
    
    /*
     * Проведение платежа
     */
    public function post3ds($post_data) {
        
        $url = $this->post3ds_url;
        $response = $this->gateway($url, $post_data);
        
        if (isset($response['Success']) && $response['Success'] == true) {
            header( 'Location: http://google.ru', true, 301 );
            exit();
//            return $response['Model']['CardHolderMessage'];
        } elseif (isset($response['Success']) && $response['Success'] == false) {
            header( 'Location: http://yandex.ru', true, 301 );
            exit();
//            return $response['Model']['ReasonCode'];
        }
        
        return false;
        
    }
}
