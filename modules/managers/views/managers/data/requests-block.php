<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Последние 10 новых заявок
 */
?>
<?php if (!empty($request_list) && count($request_list) > 0) : ?>
    <?php foreach ($request_list as $request) : ?>
        <div class="general-right__request-body">
            <h5>
                <span class="requiest-id"><?= "ID {$request['number']}" ?></span>
                <span class="requiest-date pull-right">
                    <?= FormatHelpers::formatDate($request['date_create'], true, 0, false) ?>
                </span>
            </h5>
            <div>
                <p><span class="title">Вид заявки: </span><?= $request['type_requests'] ?></p>
                <p><span class="title">Адрес:</span> 
                    <?=
                    FormatHelpers::formatFullAdress(
                            $request['gis_adress'], $request['house'], false, false, $request['flat'])
                    ?>
                </p>
                <?php if (Yii::$app->user->can('RequestsEdit')) : ?>
                    <?= Html::a('Перейти', ['requests/view-request', 'request_number' => $request['number']], ['class' => 'pull-right']) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Новых заявок нет.</p>
    </div>
<?php endif; ?>