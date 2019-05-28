<?php

/*
 * Рендер вида Квитанции ЖКУ
 */
?>
<?php if (isset($receipts_lists) && count($receipts_lists) > 0) : ?>
<ul class="list-group receipte-of-lists">
    <?php foreach ($receipts_lists as $key => $receipt) : ?>
        <?php 
            // Статус оплаты квитанции
            $status_payment = $receipt['Статус квитанции'] == 'Не оплачена' ? false : true;
            $str = $status_payment ? "Оплачено {$receipt['Сумма к оплате']}&#8381" : "Задолженность {$receipt['Сумма к оплате']}&#8381";
            
            $url_pdf = $path_to_receipts . "{$house_id}/{$receipt['Расчетный период']}/{$account_number}.pdf";
            // Получаем заголовки из ответа для загруженной квитанции
            $headers = @get_headers($url_pdf);
        ?>
    
        <li class="list-group-item <?= $key == 0 ? 'active' : '' ?>" 
                data-house="<?= $house_id ?>" 
                data-period="<?= $receipt['Расчетный период'] ?>" 
                data-account="<?= $account_number ?>">
            
            <p class="receipte-month">
                <?= Yii::$app->formatter->asDate($receipt['Расчетный период'], 'LLLL, Y') ?>
            </p>
            <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>                                
            <span class="<?= $status_payment ? 'receipte-btn-pay-ok' : 'receipte-btn-pay-debt' ?>">
                <?= $str ?>
            </span>
            
            <?php 
            // Если в заголовке имеется статус 200, квитанция на сервере существует, даем возможность ее скачать
            if (strpos($headers[0], '200')) : ?>
                <a href="<?= $url_pdf ?>" class="receipte-btn-dowload" target="_blank">
                    <i class="glyphicon glyphicon-download-alt"></i>
                </a>
            <?php endif; ?>
            
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>За указанный период квитанции не найдены.</p>
</div>
<?php endif; ?>