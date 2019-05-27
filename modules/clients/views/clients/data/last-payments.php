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
    <?php foreach ($payments as $key => $payment) : ?>
        <tr>
            <?php if ($key <= 5) : ?>
                <?php 
                    $status_payment = false;
                    // Проверяем наличие платежа в БД
                    $is_payment = Payments::getStatusPayment($payment['Расчетный период'], $this->context->_current_account_number); 
                                        
                    // Если квитанция "Не оплачена"
                    if ($payment['Статус квитанции'] == 'Не оплачена') {
                        $status_payment = $is_payment == Payments::NOT_PAID ? false : true;
                    } elseif ($payment['Статус квитанции'] == 'Оплачена') {
                        $status_payment = true;
                    }
                    // Проверяем совершался ли платеж ранее
                ?>
                <td>
                    <?php $date_period = Yii::$app->formatter->asDate($payment['Расчетный период'], 'LLLL, Y'); ?>
                    <?= $status_payment ?
                            $date_period :
                            Html::a($date_period, [
                                'payments/payment', 
                                'qw1' => base64_encode(utf8_encode($payment['Расчетный период'])), 
                                'qw2' => base64_encode(utf8_encode($payment['Номер квитанции'])), 
                                'qw3' => base64_encode(utf8_encode($payment['Сумма к оплате']))
                            ]) 
                    ?>
                </td>
                <td class="<?= $status_payment ? 'status-payment-ok' : 'status-payment-not' ?>">
                    <?= $status_payment ? 'Оплачено' : 'Не оплачено' ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p class="message-general-page">
        Лицевой счет <span><?= $this->context->_current_account_number ?></span> не содержит сведений о квитанциях.
    </p>
<?php endif; ?>


