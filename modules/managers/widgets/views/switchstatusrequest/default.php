<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/*
 * Выпадающий список переключения статуса заявки
 */
?>

<div class="btn-group btn-group_status-request" id="status-value-<?= $status ?>">
    <div class="btn-group">
        <button id="value-btn" type="button" class="btn btn-choose-status dropdown-toggle add-border-<?= $status ?>" data-status="<?= $status ?>" data-toggle="dropdown">
            <?= FormatHelpers::statusName($status) ?> 
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-status" role="menu">
            <?php foreach ($array_status as $key => $value) : ?>
                <li class="<?= ($key == $status) ? 'disabled' : '' ?>" id="status<?= $key ?>">
                    <?= Html::a($value, ['#'], [
                            'class' => 'switch-request switch-request-status',
                            'data' => [
                                'status' => $key,
                                'request' => $request,
                                'type-request' => $type_request,
                            ],
                    ]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <button type="button" class="btn btn-choose-status">
        <?= FormatHelpers::formatDate($date_update, true, 1, false) ?>
    </button>    
</div>
<?php
/*
 * Временный костыль, блокирует функциональные кнопки заявки
 */
//$this->registerJs('
//    if ("' . $status . '" == 4) {
//        $(".btn:not(.dropdown-toggle)").attr("disabled", true);
//    }
//')
?>