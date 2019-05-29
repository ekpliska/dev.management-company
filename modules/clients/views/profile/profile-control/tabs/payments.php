<?php

/* 
 * Вкладка "История платежей"
 */
?>
<div class="table-container">
    <table class="table clients-table without-border">
        <thead>
            <tr>
                <th>Расчетный месяц</th>
                <th>Дата платежа</th>
                <th>Тип оплаты</th>
                <th>Сумма платежа</th>
            </tr>
        </thead>
        <tbody id="payments-lists">
            <?php if (!empty($payment_history) && is_array($payment_history)) : ?>
            <?php foreach ($payment_history as $key => $payment) : ?>
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
        </tbody>
    </table>
</div>