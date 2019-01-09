<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/*
 * Рендер вида Квитанции ЖКУ Собственника
 */
// Текущая дата (Год и номер месяца)
$current_date = date('Y-m');
?>
<?php if (isset($receipts_lists)) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php
            $date = new DateTime($receipt['Расчетный период']);
            // Сравниваем текущаю дату и дату расчетного периода квитанции
            $_date = ($current_date == $date->format('Y-m')) ? true : false;
            // Статус квитанции
            $status = $receipt['Статус квитанции'] == 'Не оплачено' ? false : true;
            
            $str = ($_date == true && $status == false) ? $receipt['Сумма к оплате'].'&#8381"' : "Оплачено {$receipt['Сумма к оплате']}&#8381";
            
            $url_pdf = '@web/receipts/' . $account_number . '/' . $account_number . '-' . $receipt['Номер квитанции'] . '.pdf';
        ?>
        <li class="list-group-item" data-receipt="<?= $receipt['Номер квитанции'] ?>" data-account="<?= $account_number ?>">
            <p class="receipte-month"><?= $date->format('F Y') ?></p>
            <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>
            <?php if ($_date == true && $status == false) : // Если оплата за текущий расчетный период ?>
                <?= Html::a("К оплате: {$receipt['Сумма к оплате']} &#8381", ['#'], [
                        'class' => 'receipte-btn-pay',
                    ]) 
                ?>
            <?php elseif ($_date == false && $status == false) : // Если оплата квитанции просрочена ?>
                <span class="receipte-btn-pay-debt">
                    <?= "Задолженность: {$receipt['Сумма к оплате']} &#8381" ?>
                </span>
            <?php elseif ($_date == false && $status == true) : // Если оплата квитанции была совершена ?>
                <span class="receipte-btn-pay-ok">
                    <?= "Оплачено: {$receipt['Сумма к оплате']} &#8381" ?>
                </span>
            <?php endif; ?>
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