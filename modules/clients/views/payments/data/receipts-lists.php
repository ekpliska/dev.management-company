<?php

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
            $date = new DateTime($receipt['receipt_period']);
            // Сравниваем текущаю дату и дату расчетного периода квитанции
            $_date = ($current_date == $date->format('Y-m')) ? true : false;
            // Платежа по квитанции
            $status = $receipt['status_payment'];
            
            // Формируем путь к PDF-квитанции
            $url_pdf = $path_to_receipts . "{$house_id}/{$receipt['receipt_period']}/{$account_number}.pdf";
            // Получаем заголовки из ответа для загруженной квитанции
            $headers = @get_headers($url_pdf);
        ?>
    
        <li class="list-group-item <?= $key == 0 ? 'active' : '' ?>" data-period="<?= $receipt['receipt_period'] ?>" data-house="<?= $house_id ?>">
            
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

                        <?php 
                        // Если в заголовке имеется статус 200, квитанция на сервере существует, даем возможность отправить ее по почте
                        if (strpos($headers[0], '200')) : ?>
                            <?= Html::button('Отправить <i class="fa fa-paper-plane-o" aria-hidden="true"></i>', [
                                    'class' => 'send_receipt',
                                    'data-house' => $house_id,
                                    'data-period' => $receipt['receipt_period']]) ?>
                        <?php endif; ?>
                        
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
                    <?php 
                        // Если оплата квитанции была совершена, или же квитанцию за текущий приеод еще не оплатили
                        elseif ( ($_date == false && $status == true) || ($_date == true && $status == true)) : ?>
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