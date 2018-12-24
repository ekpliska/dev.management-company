<?php

    use yii\helpers\Url;

?>
<?php if (isset($receipts_lists)) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php
            $date = new DateTime($receipt['Дата оплаты']);
            $str = $date ? "Оплачено {$receipt['Сумма к оплате']}&#8381" : $receipt['Сумма к оплате'].'&#8381"';
            $url_pdf = '@web/receipts/' . $account_number . '/' . $account_number . '-' . $receipt['Номер квитанции'] . '.pdf';
        ?>
        <li class="list-group-item" data-receipt="<?= $receipt['Номер квитанции'] ?>" data-account="<?= $account_number ?>">
            <p class="receipte-month"><?= $date ? $date->format('F Y') : date('F Y') ?></p>
            <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>                                
            <span class="<?= $date ? 'receipte-btn-pay-ok' : 'receipte-btn-pay' ?>">
                <?= $str ?>
            </span>
            <a href="<?= Url::to($url_pdf) ?>" class="receipte-btn-dowload" target="_blank">
                <i class="glyphicon glyphicon-download-alt"></i>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>