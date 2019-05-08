<?php

    namespace app\modules\api\v1\models\payments;
    use Yii;
    use yii\helpers\Url;
    use app\models\Payments as BasePayments;
    use app\models\PersonalAccount;    

/**
 * Платежи
 */
class Payments extends BasePayments {
    
    /*
     * Находим платеж. по переданным данным - устанавливаем ему статус Оплачено
     * 
     * Формируем данные для отпавки на API метод на платежный шлюз для проведения платежа
     */
    public static function setStatusPayment($md, $pa_res, $account_number, $period) {
        
        $account = PersonalAccount::findOne(['account_number' => $account_number]);
        if (!$account) {
            header( 'Location: ' . Url::toRoute(['/site/result', 'status' => 'unsuccess']), true, 301);
            exit();
        }
        
        // Находим платеж, меняем ему статус
        $payment = self::find()
                ->andWhere([
                    'receipt_period' => $period, 
                    'account_uid' => $account->account_id])
                ->one();
        $payment->payment_status = self::YES_PAID;
        $payment->save(false);
        
        // Формируем данные для отправки в платежный шлюз для проведения платежа
        $data_posts = [
            'TransactionId' => $md,
            'PaRes' => $pa_res
        ];
        
        if (!$data_posts) {
            header('Location: ' . Url::toRoute(['/site/result', 'status' => 'unsuccess']), true, 301);
            exit();
        }
        // Вызываем API метод на завершение платежа
        Yii::$app->paymentSystem->post3ds($data_posts);
        
        return true;
        
    }
    
}
