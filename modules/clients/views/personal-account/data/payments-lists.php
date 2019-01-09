<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php 
//echo '<pre>';
//var_dump($payments_lists); 
?>
<?php if (empty($payments_lists) || count($payments_lists) > 0) : ?>
<?php foreach ($payments_lists as $key => $payment) : ?>
<tr>
    <td>
        <?= Yii::$app->formatter->asDate($payment['Расчетный период'], 'LLLL Y') ?>
    </td>
    <td>
        <?= Yii::$app->formatter->asDate($payment['Дата платежа'], 'long') ?>
    </td>
    <td>
        <?= $payment['Тип оплаты'] ?>
    </td>
    <td>
        <?= $payment['Сумма платежа'] . '&#8381;' ?>
    </td>
</tr>
<?php endforeach; ?>
<?php else : ?>
<tr>
    <td colspan="4" class="status-not-found">
        Данный лицевой счет не содержит историю платежей или платежи не найдены.
    </td>
</tr>
<?php endif; ?>