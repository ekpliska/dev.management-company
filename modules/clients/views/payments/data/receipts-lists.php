<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\clients\widgets\ModalWindows;

/*
 * Рендер вида Квитанции ЖКУ Собственника
 */
// Текущая дата (Год и номер месяца)
$current_date = date('Y-m');
?>
<?php if (!empty($receipts_lists) || count($receipts_lists) > 0) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php
            $date = new DateTime($receipt['Расчетный период']);
            // Сравниваем текущаю дату и дату расчетного периода квитанции
            $_date = ($current_date == $date->format('Y-m')) ? true : false;
            // Статус квитанции
            $status = $receipt['Статус квитанции'] == 'Оплачена' ? true : false;
            // Формируем ссылку на PDF квитанцию
            $url_pdf = Yii::getAlias('@web') . '/web' . '/receipts/' . $account_number . '/' . $receipt['Расчетный период'] . '.pdf';
        ?>
        <li class="list-group-item" data-receipt="<?= $receipt['Расчетный период'] ?>" data-account="<?= $account_number ?>">
            <p class="receipte-month"><?= $date->format('F Y') ?></p>
            <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>
            <?php if ($_date == true && $status == false) : // Если оплата за текущий расчетный период ?>
                <?= Html::a("К оплате: {$receipt['Сумма к оплате']} &#8381", ['personal-account/payment'], [
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
            
            <?php /*
            <div class="receipte-of-lists__operations">
                <?php if ($status == false) : // Кнопка Оплатить выводим только для неоплаченных квитанций ?>
                    <?= Html::a('<i class="glyphicon glyphicon-ruble"></i> Оплатить</a>', [
                            'payments/payment', 
                            'period' => urlencode($receipt['Расчетный период']), 
                            'nubmer' => urlencode($receipt['Номер квитанции']), 
                            'sum' => urlencode($receipt['Сумма к оплате'])
                        ]) ?>
                <?php endif; ?>
                
                <a href="<?= Url::to($url_pdf, true) ?>" class="send_receipt" data-number-receipt="<?= $receipt['Номер квитанции'] ?>">
                    <i class="glyphicon glyphicon-send"></i> Отправить
                </a>
                    
                <a href="<?= Url::to($url_pdf, true) ?>" class="print_receipt"><i class="glyphicon glyphicon-print"></i> Распечатать</a>
            </div>
             */ ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>За указанный период квитанции не найдены.</p>
</div>
<?php endif; ?>

<?= ModalWindows::widget(['modal_view' => 'default_dialog']) ?>