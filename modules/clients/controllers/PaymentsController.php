<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Response;
    use app\modules\clients\controllers\AppClientsController;
    use app\components\mail\Mail;

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
            Mail::send($user_email, "Квитанция {$date_receipt}", 'SendReceipt', $file_name = $file_url, ['receipt_number' => $date_receipt]);
            return ['success' => true];
        }
        
        return ['success' => false];
        
    }    
    
}
