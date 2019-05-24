<?php

    use yii\helpers\Html;
    use app\models\Payments;

/* 
 * Последний 6 платежей по квитанция
 */

$count_payment = 6;
$status_payment;
$date_period = '';
?>
<?php if(!empty($payments) && is_array($payments)) : ?>
<table class="table table-last-payment">
    <tbody>
        <tr>
            <?php foreach ($payments as $key => $payment) : ?>
            
            <?php if ($key <= 5) : ?>
                <?php 
                    // Проверяем совершался ли платеж ранее
                    $is_payment = Payments::getStatusPayment($payment['Расчетный период'], $this->context->_current_account_number); 
                    $status_payment = $is_payment == Payments::YES_PAID ? true : false;
                ?>
                <td>
                    <?php $date_period = Yii::$app->formatter->asDate($payment['Расчетный период'], 'LLLL, Y'); ?>
                    <?= $status_payment ?
                            $date_period :
                            Html::a($date_period, [
                                'payments/payment', 
                                'period' => base64_encode(utf8_encode($payment['Расчетный период'])), 
                                'nubmer' => base64_encode(utf8_encode($payment['Номер квитанции'])), 
                                'sum' => base64_encode(utf8_encode($payment['Сумма к оплате']))
                            ]) 
                    ?>
                </td>
                <td class="<?= $status_payment ? 'status-payment-ok' : 'status-payment-not' ?>">
                    <?= $status_payment ? 'Оплачено' : 'Не оплачено' ?>
                </td>
            <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    </tbody>
</table>
<?php else: ?>
    <p class="message-general-page">
        Лицевой счет <span><?= $this->context->_current_account_number ?></span> не содержит сведений о квитанциях.
    </p>
<?php endif; ?>


