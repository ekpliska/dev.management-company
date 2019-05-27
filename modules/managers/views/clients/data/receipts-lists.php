<?php

    use yii\helpers\Url;

/*
 * Рендер вида Квитанции ЖКУ
 */
?>
<?php if (!empty($receipts_lists) || count($receipts_lists) > 0) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php 
            // Статус оплаты квитанции
            $status_payment = $receipt['Статус квитанции'] == 'Не оплачена' ? false : true;
            $str = $status_payment ? "Оплачено {$receipt['Сумма к оплате']}&#8381" : "Задолженность {$receipt['Сумма к оплате']}&#8381";
            $url_pdf = Yii::getAlias('@web') . '/receipts/' . $account_number . '/' . $receipt['Расчетный период'] . '.pdf';
        ?>
        <li class="list-group-item <?= $key == 0 ? 'active' : '' ?>" data-receipt="<?= $receipt['Расчетный период'] ?>" data-account="<?= $account_number ?>">
            <p class="receipte-month">
                <?= Yii::$app->formatter->asDate($receipt['Расчетный период'], 'LLLL, Y') ?>
            </p>
            <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>                                
            <span class="<?= $status_payment ? 'receipte-btn-pay-ok' : 'receipte-btn-pay-debt' ?>">
                <?= $str ?>
            </span>
            <a href="<?= Url::to($url_pdf) ?>" class="receipte-btn-dowload" target="_blank">
                <i class="glyphicon glyphicon-download-alt"></i>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>За указанный период квитанции не найдены.</p>
</div>
<?php endif; ?>