<?php

    use yii\helpers\Html;

/* 
 * Последний 6 платежей по квитанция
 */

$count_payment = 6;
$status_payment;
//echo '<pre>'; var_dump($payments);
?>
<?php if(!empty($payments) && is_array($payments)) : ?>
<table class="table table-last-payment">
    <tbody>
        <tr>
            <?php foreach ($payments as $key => $payment) : ?>
            <?php $status_payment = $payment['Статус квитанции'] == 'Не оплачена' ? false : true; ?>
            <td>
                <?= $status_payment ?
                        $payment['Расчетный период'] :
                        Html::a($payment['Расчетный период'], [
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
            <?php endforeach; ?>
        </tr>
    </tbody>
</table>
<?php endif; ?>


