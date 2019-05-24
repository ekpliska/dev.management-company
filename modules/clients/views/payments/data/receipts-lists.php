<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\clients\widgets\ModalWindows;

/*
 * Рендер вида Квитанции ЖКУ Собственника
 */
// Текущая дата (Год и номер месяца)
$current_date = date('Y-m', strtotime("-1 month"));
?>


<?php if (!empty($receipts_lists) || count($receipts_lists) > 0) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php
            $date = new DateTime($receipt['receipt_period']);
            // Сравниваем текущаю дату и дату расчетного периода квитанции
            $_date = ($current_date == $date->format('Y-m')) ? true : false;
            // Платежа по квитанции
            $status = $receipt['status_payment'];
            // Формируем ссылку на PDF квитанцию
            $url_pdf = Yii::getAlias('@web') . '/receipts/' . $account_number . '/' . $receipt['receipt_period'] . '.pdf';
        ?>
        <li class="list-group-item <?= $key == 0 ? 'active' : '' ?>" data-receipt="<?= $receipt['receipt_period'] ?>" data-account="<?= $account_number ?>">
            
            <div class="receipt-item">
                <div class="receipt-item__info">
                    <div class="receipt-item__info-head">
                        <p class="receipte-month"><?= Yii::$app->formatter->asDate($receipt['receipt_period'], 'LLLL, Y') ?></p>
                        <p class="receipte-number">Квитанция <?= $receipt['receipt_num'] ?></p>
                    </div>
                    <div class="receipt-item__info-footer">

                        <?php if ($status == false) : // Кнопка Оплатить выводим только для неоплаченных квитанций ?>
                            <?= Html::a('Оплатить <i class="fa fa-rub" aria-hidden="true"></i>', [
                                    'payments/payment',
                                            'qw1' => base64_encode(utf8_encode($receipt['receipt_period'])),
                                            'qw2' => base64_encode(utf8_encode($receipt['receipt_num'])),
                                            'qw3' => base64_encode(utf8_encode($receipt['receipt_summ']))], 
                                    ['class' => 'receipt-item__btn-pay']) ?>
                        <?php endif; ?>

                        <a href="<?= Url::to($url_pdf, true) ?>" class="send_receipt" data-number-receipt="<?= $receipt['receipt_num'] ?>">
                            Отправить <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                        </a>
                        
                    </div>
                </div>
                <div class="receipt-item__status">
                    <?php if ($_date == true && $status == false) : // Если оплата за текущий расчетный период ?>
                        <span class="receipte-info-pay">
                            <span>К оплате</span>
                            <?= "{$receipt['receipt_summ']}&#8381" ?>
                        </span>
                    <?php elseif ($_date == false && $status == false) : // Если оплата квитанции просрочена ?>
                        <span class="receipte-info-pay-debt">
                            <span>Задолженность</span>
                            <?= "{$receipt['receipt_summ']}&#8381" ?>
                        </span>
                    <?php elseif ($_date == false && $status == true) : // Если оплата квитанции была совершена ?>
                        <span class="receipte-info-pay-ok">
                            <span>Оплачено</span>
                            <?= "{$receipt['receipt_summ']}&#8381" ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>За указанный период квитанции не найдены.</p>
</div>
<?php endif; ?>

<?= ModalWindows::widget(['modal_view' => 'default_dialog']) ?>