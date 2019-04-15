<?php

    use yii\helpers\Html;

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
                <?php $status_payment = $payment['Статус квитанции'] == 'Не оплачена' ? false : true; ?>
                <td>
                    <?php $date_period = Yii::$app->formatter->asDate($payment['Расчетный период'], 'LLLL, Y'); ?>
                    <?= $status_payment ?
                            $date_period :
                            Html::a($date_period, [
                                'payments/payment', 
                                'period' => urlencode($payment['Расчетный период']), 
                                'nubmer' => urlencode($payment['Номер квитанции']), 
                                'sum' => urlencode($payment['Сумма к оплате'])
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


