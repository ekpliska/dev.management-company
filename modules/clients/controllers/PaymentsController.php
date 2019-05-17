<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\clients\controllers\AppClientsController;
    use app\components\mail\Mail;
    use app\models\Payments;
    use app\models\Organizations;

/**
 * Платежи и квитанции
 */
class PaymentsController extends AppClientsController {
    
    public function actionIndex() {
        
        $user_info = $this->permisionUser();
        
        // Получить номер текущего лицевого счета
        $account_number = $this->_current_account_number;
        
        // Получаем номер текущего месяца и год
        $current_period = date('Y-n');
        
        $array_request = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => null,
            'Период конец' => $current_period,
        ];
        
        $data_json = json_encode($array_request, JSON_UNESCAPED_UNICODE);
        
        $receipts_lists = Yii::$app->client_api->getReceipts($data_json);
        
        return $this->render('index', [
            'account_number' => $account_number,
            'receipts_lists' => $receipts_lists ? $receipts_lists : null,
        ]);
        
    }
    
    /*
     * Отправка квитанции на электронную почту пользователя
     */
    public function actionSendReceiptToEmail() {
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $file_url = Yii::$app->request->post('fileUrl');
        $date_receipt = Yii::$app->request->post('dateReceipt');
        $user_email = Yii::$app->userProfile->email;
        
        if (empty($user_email)) {
            return [
                'success' => false,
            ];
        }
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Mail::send($user_email, "Квитанция {$date_receipt}", 'SendReceipt', $file_url, ['receipt_number' => $date_receipt]);
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }
    
    /*
     * Страница "Платеж" (форма оплаты)
     * 
     * @param integer $period Расчетный период квитанции
     * @param string $nubmer Номер квитанции
     * @param decimal $sum Сумма к оплате по квитанции
     */
    public function actionPayment($period, $nubmer, $sum) {
        
        $_period = urldecode($period);
        $_nubmer = urldecode($nubmer); 
        $_sum = urldecode($sum);
        
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
        
        $date_start = Yii::$app->formatter->asDate($date_start, 'YYYY-MM-d');
        $date_end = Yii::$app->formatter->asDate($date_end, 'YYYY-MM-d');
                
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!is_numeric($account_number)) {
            return ['success' => false];
        }
        
        $data_array = [
            'Номер лицевого счета' => $account_number,
            'Период начало' => $date_start,
            'Период конец' => $date_end
        ];        
        $data_json = json_encode($data_array, JSON_UNESCAPED_UNICODE);
        
        if (Yii::$app->request->isPost) {
            
            switch ($type) {
                case 'receipts':
                    $results = Yii::$app->client_api->getReceipts($data_json);
                    break;
                case 'payments':
                    $results = Yii::$app->client_api->getPayments($data_json);
                    break;
                default:
                    $results['status'] == 'error';
                    break;
            }
            
            $data_render = $this->renderPartial('data/' . $type . '-lists', [
                $type . '_lists' => $results ? $results : null,
                'account_number' => $account_number]);
            
            return [
                'success' => true,
                'data_render' => $data_render,
                'results' => $results,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ];
        }
        
        return ['success' => false];
        
    }
    
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
    
}
