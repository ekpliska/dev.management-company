<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Последние 10 новых заявок на платные услуги
 */
?>
<?php if (!empty($paid_request_list) && count($paid_request_list) > 0) : ?>
    <?php foreach ($paid_request_list as $paid_request) : ?>
        <div class="general-right__request-body">
            <h5>
                <span class="requiest-id"><?= "ID {$paid_request['number']}" ?></span>
                <span class="requiest-date pull-right">
                    <?= FormatHelpers::formatDate($paid_request['date_create'], true, 0, false) ?>
                </span>
            </h5>
            <div>
                <p><span class="title">Категория, услуга: </span><?= "{$paid_request['category']}, {$paid_request['service_name']}" ?></p>
                <p><span class="title">Адрес:</span> 
                    <?=
                    FormatHelpers::formatFullAdress(
                            $paid_request['gis_adress'], $paid_request['house'], false, false, $paid_request['flat'])
                    ?>
                </p>
                <?php if (Yii::$app->user->can('PaidRequestsEdit')) : ?>
                    <?= Html::a('Перейти', ['paid-requests/view-paid-request', 'request_number' => $paid_request['number']], ['class' => 'pull-right']) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Новых заявок на платные услуги нет.</p>
    </div>
<?php endif; ?>