<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use yii\web\NotFoundHttpException;
    use app\modules\clients\controllers\AppClientsController;
    use app\components\mail\Mail;
    use app\models\Payments;
    use app\models\Organizations;
    use app\models\SiteSettings;

/**
 * Платежи и квитанции
 */
class PaymentsController extends AppClientsController {
    
    public function actionIndex() {
        
        // Получить номер текущего лицевого счета
        $account_number = $this->_current_account_number;
        // Получить ID дома для текущего ЛС (используется для формирования списка квитанций)
        $house_id = Yii::$app->userProfile->getLivingSpace($this->_current_account_id);
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-n');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        
        $receipts_lists = [
            '0' => [
                "Расчетный период" => "2018-10-06",
                "Номер квитанции" => "06/2018",
                "Сумма к оплате" => "4782.00",
                "Статус квитанции" => "not_paid",
            ],
            '1' => [
                "Расчетный период" => "2018-10-07",
                "Номер квитанции" => "07/2018",
                "Сумма к оплате" => "5000.00",
                "Статус квитанции" => "not_paid",
            ],
        ];

        /*
         * Перепроверяем полученные квитанции,
         * если у квитанции статус "Не оплачена", то проверяем наличие платежа в БД
         */
        $results = [];
        if (isset($receipts_lists) && !$receipts_lists == null) {
            foreach ($receipts_lists as $key => $receipt) {
                if ($receipt['Статус квитанции'] === 'Не оплачена') {
                    $results[] = [
                        'receipt_period' => $receipt['Расчетный период'],
                        'receipt_num' => $receipt['Номер квитанции'],
                        'receipt_summ' => $receipt['Сумма к оплате'],
                        'receipt_status' => $receipt['Статус квитанции'],
                        'status_payment' => Payments::getStatusPayment($receipt['Расчетный период'], $account_number) == Payments::YES_PAID ? true : false,
                    ];
                } else {
                    $results[] = [
                        'receipt_period' => $receipt['Расчетный период'],
                        'receipt_num' => $receipt['Номер квитанции'],
                        'receipt_summ' => $receipt['Сумма к оплате'],
                        'receipt_status' => $receipt['Статус квитанции'],
                        'status_payment' => false,
                    ];
                }
            }
        }
        
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        
        return $this->render('index', [
            'account_number' => $account_number,
            'house_id' => $house_id['houses_id'],
            'receipts_lists' => $results ? $results : null,
            'path_to_receipts' => $path_to_receipts,
        ]);
        
    }
    
    /*
     * Отправка квитанции на электронную почту пользователя
     */
    public function actionSendReceiptToEmail() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Текущий номер лицевого счета
        $account_number = $this->_current_account_number;
        // Получаем расчетный период из AJAX
        $period_receipt = Yii::$app->request->post('period');
        // Получаем ID дома из AJAX
        $house_id = Yii::$app->request->post('house');
        
        // Формируем путь к квитанции
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        $file_url = $path_to_receipts . "{$house_id}/{$period_receipt}/{$account_number}.pdf";
        $headers = @get_headers($file_url);
        
        // Получаем электронный адрес текущего пользователя
        $user_email = Yii::$app->userProfile->email;
        
        // Проверяем существование PDF документа на сервере и адрес электронной почты
        if (!strpos($headers[0], '200') || empty($user_email)) {
            return ['success' => false];
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Mail::send($user_email, "Квитанция {$period_receipt}", 'SendReceipt', $file_url, ['receipt_number' => $period_receipt]);
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Страница "Платеж" (форма оплаты)
     * 
     * @param integer $qw1 Расчетный период квитанции
     * @param string $qw2 Номер квитанции
     * @param decimal $qw3 Сумма к оплате по квитанции
     */
    public function actionPayment($qw1, $qw2, $qw3) {
        
        $_period = base64_decode($qw1);
        $_nubmer = base64_decode($qw2); 
        $_sum = base64_decode($qw3);
        
        if (!$_period || !$_nubmer || !$_sum) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Данные для платежной системы
        $public_id = isset(Yii::$app->paymentSystem->public_id) ? Yii::$app->paymentSystem->public_id : null;
        $description = isset(Yii::$app->paymentSystem->description) ? Yii::$app->paymentSystem->description : null;
        if ($public_id == null || $description == null) {
            return $this->render('payment', [
                'paiment_info' => 'error',
            ]);
        }
        
        // Получаем ID текущего лицевого счета
        $accoint_id = $this->_current_account_id;
        
        // Проверяем наличие платежа и его статус
        $paiment_info = Payments::isPayment($_period, $_nubmer, $_sum, $accoint_id);
        
        // Если статус платежа Оплачено
        if ($paiment_info['status'] == Payments::YES_PAID) {
            return $this->render('payment', [
                'paiment_info' => $paiment_info['payment'],
            ]);
        } elseif ($paiment_info['status'] == Payments::NOT_PAID) { 
            // Если статус платежа Не оплачено
            $organization_info = Organizations::findOne(['organizations_id' => 1]);
            return $this->render('payment', [
                'paiment_info' => $paiment_info['payment'],
                'organization_info' => $organization_info,
                'public_id' => $public_id,
                'description' => $description,
            ]);
            
        }
        
    }
    
    /*
     * Запрос на получение квитанций по заданному лицевому счету и диапазону
     * 
     * Формируем запрос, преобразуем в JSON, отправляем по API:
     * $data_array = [
     *      "Номер лицевого счета" => $account_number,
     *      "Период начало" => $date_start,
     *      "Период конец" => $date_end
     * ]
     * 
     */
    public function actionSearchDataOnPeriod($account_number, $date_start, $date_end, $type) {
        
        $date_start = Yii::$app->formatter->asDate($date_start, 'YYYY-MM');
        $date_end = Yii::$app->formatter->asDate($date_end, 'YYYY-MM');
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $data_array = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => $date_start,
            'Период конец' => $date_end
        ];        
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            
            switch ($type) {
                case 'receipts':
                    $_results = Yii::$app->client_api->getReceipts($data_json);
                    $results = [];
                    if (isset($_results) && !$_results == null) {
                        foreach ($_results as $key => $receipt) {
                            if ($receipt['Статус квитанции'] === 'Не оплачена') {
                                $results[] = [
                                    'receipt_period' => $receipt['Расчетный период'],
                                    'receipt_num' => $receipt['Номер квитанции'],
                                    'receipt_summ' => $receipt['Сумма к оплате'],
                                    'receipt_status' => $receipt['Статус квитанции'],
                                    'status_payment' => Payments::getStatusPayment($receipt['Расчетный период'], $account_number) == Payments::YES_PAID ? true : false,
                                ];
                            } else {
                                $results[] = [
                                    'receipt_period' => $receipt['Расчетный период'],
                                    'receipt_num' => $receipt['Номер квитанции'],
                                    'receipt_summ' => $receipt['Сумма к оплате'],
                                    'receipt_status' => $receipt['Статус квитанции'],
                                    'status_payment' => true,
                                ];
                            }
                        }
                    }
                    break;
                case 'payments':
                    $results = Yii::$app->client_api->getPayments($data_json);
                    break;
                default:
                    $results['status'] == 'error';
                    break;
            }
            // Получить ID дома для текущего ЛС (используется для формирования списка квитанций)
            $house_id = Yii::$app->userProfile->getLivingSpace($this->_current_account_id);
            $path_to_receipts = SiteSettings::getReceiptsUrl();
            
            $data_render = $this->renderPartial('data/' . $type . '-lists', [
                $type . '_lists' => $results ? $results : null,
                'account_number' => $account_number,
                'house_id' => $house_id['houses_id'],
                'path_to_receipts' => $path_to_receipts,
            ]);
            
            return [
                'success' => true,
                'data_render' => $data_render
            ];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Смена статуса платежа
     */
    public function actionPaymentTransaction() {
        
        $payment_id = Yii::$app->request->post('paymentID');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $payment = Payments::findOne(['unique_number' => $payment_id]);
            if ($payment->changeStatus()) {
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->goHome();
        }
        
    }
    
    /*
     * Получение URL для квитанции
     */
    public function actionGetReceiptPdf() {
        
        $house_id = Yii::$app->request->post('house');
        $period = Yii::$app->request->post('period');
        // Текущий номер лицевого счета
        $account_number = $this->_current_account_number;
        
        $path_to_receipts = SiteSettings::getReceiptsUrl();
        $path_url = $path_to_receipts . "{$house_id}/{$period}/{$account_number}.pdf";
        $headers = @get_headers($path_url);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            if (strpos($headers[0], '200')) {
                return ['success' => true, 'url' => $path_url];
            }
            return ['success' => false];
        }
        
        return ['success' => false];
        
    }
    
}
