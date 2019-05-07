<?php

    namespace app\modules\api\v1\models\payments;
    use Yii;
    use app\models\Payments;
    use app\models\PersonalAccount;

/**
 * Платежи
 */
class Payments extends Payments {
    
    /*
     * Находим платеж. по переданным данным - устанавливаем ему статус Оплачено
     * 
     * Формируем данные для отпавки на API метод на платежный шлюз для проведения платежа
     */
    public static function setStatusPayment($md, $pa_res, $account_number, $period) {
        
        $account = PersonalAccount::findOne(['account_number' => $account_number]);
        if (!$account) {
            return false;
        }
        
        // Находим платеж, меняем ему статус
        $payment = self::find()
                ->andWhere([
                    'receipt_period' => $period, 
                    'account_uid' => $account->account_id,
                    'user_uid' => Yii::$app->user->identity->id])
                ->one();
        $payment->payment_status = self::YES_PAID;
        $payment->save(false);
        
        
        // Формируем данные для отправки в платежный шлюз для проведения платежа
        $data_posts = [
            'TransactionId' => $md,
            'PaRes' => $pa_res
        ];
        
        if (!$result = Yii::$app->paymentSystem->post3ds($data_posts)) {
            return "<h1>Ошибка</h1>";
        }
        return "<h1>{$result}</h1>";
    }
    
}
