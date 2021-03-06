<?php

    namespace app\components\paymentSystem;
    use Yii;
    use app\models\Payments;
    use yii\helpers\Url;

/*
 * Интеграция с платежной системой Cloudpayments
 */
class PaymentSystem {
        
    /**
     * ДАННЫЕ ДЛЯ ПОДКЛЮЧЕНИЯ К ПЛАТЕЖНОМУ ШЛЮЗУ
     *
     * public_id        Логин магазина, полученный при подключении.
     * api_secret       Пароль магазина, полученный при подключении.
     * gateway_url      Метод для передачи сформированной криптограммы, пришедшей с мобильного устройства
     * post3ds_url      Метод для завершения оплаты
     */
    
    public $public_id;
    public $api_secret;
    // Метод для оплаты по криптограмме
    public $gateway_url = 'https://api.cloudpayments.ru/payments/cards/charge';
    // Метод для проведение платежа
    public $post3ds_url = 'https://api.cloudpayments.ru/payments/cards/post3ds';
    // Описание платежа
    public $description = 'Оплата услуг ЖКХ';
    /*
     *  Ставка НДС
     *      ''       НДС не облагается
     *      0        НДС 0%
     *      10       НДС 10%
     *      20       НДС 20%
     *      110      НДС 110%
     *      120      НДС 120%
     */
    public $vat = 0;
    /*
     *  Признак способа расчета
     *      0	Unknown, неизвестный способ расчета
     *      1	FullPrepayment, Предоплата 100%
     *      2	PartialPrepayment, Предоплата
     *      3	AdvancePay, Аванс
     *      4	FullPay, Полный расчёт
     *      5	PartialPayAndCredit, Частичный расчёт и кредит
     *      6	Credit, Передача в кредит
     *      7	CreditPayment, Оплата кредита
     */
    public $method = 4;
    /*
     *  Признак Предметы расчета
     *      0	Unknown, неизвестный предмет оплаты
     *      1	Commodity, Товар
     *      2	ExcisedCommodity, Подакцизный товар
     *      3	Job, Работа
     *      4	Service, Услуга
     *      5	GamblingBet, Ставка азартной игры
     *      6	GamblingWin, Выигрыш азартной игры
     *      7	LotteryTicket, Лотерейный билет
     *      8	LotteryWin, Выигрыш лотереи
     *      9	RidAccess, Предоставление РИД
     *      10	Payment, Платеж
     *      11	AgentReward, Агентское вознаграждение
     *      12	Composite, Составной предмет расчета
     *      13	Another, Иной предмет расчета
     */
    public $object = 10;

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
            // Если была передана некорректная крипрограмма
            if ($response['Message'] != null) {
                return [
                    'success' => false,
                    'message' => $response['Message'],
                ];                
            }
            /*
             * Если возникает ошибка с картой без 3-D Secure, ошибку выводим в ключе message, 
             * иначе карта с 3-D Secure, данне для продолжения платежа выводим в ключе secure_info
             */
            $key_text = isset($response['Model']['CardHolderMessage']) ? 'message' : 'secure_info';
            return [
                'success' => true,
                $key_text => isset($response['Model']['CardHolderMessage']) ? $response['Model']['CardHolderMessage'] : $response['Model'],
            ];
        } elseif ($response['Success'] == true) {
            // Если карта без 3-D Secure устанавливаем платежу статус Оплачен
            $payment = Payments::findOne(['unique_number' => $payment_number]);
            if ($payment) {
                $payment->changeStatus();
            }
            return [
                'success' => true,
                'message' => isset($response['Model']['CardHolderMessage']) ? $response['Model']['CardHolderMessage'] : $response['Model']['CardHolderMessage'],
            ];            
        }
        
    }
    
    /*
     * Проведение платежа
     */
    public function post3ds($post_data) {

        $url = $this->post3ds_url;
        $response = $this->gateway($url, $post_data);
        
        $message = isset($response['Model']['CardHolderMessage']) ? $response['Model']['CardHolderMessage'] : $response['Model']['CardHolderMessage'];
        Yii::$app->session->set('payment_message', $message);
        
        if (isset($response['Success']) && $response['Success'] == true) {
            header('Location: ' . Url::toRoute(['/site/result', 'status' => 'success']), true, 301);
            exit();
        } elseif (isset($response['Success']) && $response['Success'] == false) {
            header('Location: ' . Url::toRoute(['/site/result', 'status' => 'unsuccess']), true, 301);
            exit();
        }
        
    }
}
