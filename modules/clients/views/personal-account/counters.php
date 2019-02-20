<?php

    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;

/* 
 * Приборы учета
 */

$this->title = Yii::$app->params['site-name'] . 'Показания приборов учета';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = 'Показания приборов учета';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<?= AlertsShow::widget() ?>

<div class="counters-page row">

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 options-panel">
        <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Месяц и Год</p>
        <?= DatePicker::widget([
                'id' => 'date_start-period-counter',
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => '',
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'MM-yyyy'
                ]
            ]);        
        ?>        
    </div>
    
    <?= $this->render('data/grid-counters', [
            'indications' => $indications,
            'auto_request' => $auto_request,
            'is_current' => $is_current,
            'model_indication' => $model_indication,
    ]) ?>

    <?php if (isset($comments_to_counters)) : ?>
    <div class="col-md-12 counters-message">
        <p class="title">
            <?= $comments_to_counters['comments_title'] ?>
        </p>
        <p class="text">
            <?= $comments_to_counters['comments_text'] ?>
        </p>
    </div>
    <?php endif; ?>
    
</div>

<?php
//$this->registerJs("
//    $('#send-indications-form').on('beforeSubmit.yii', function (e) {        
//        if ($('#send-indications-form').hasClass('has-error')) {
//            e.preventDefault();
//            return false;
//        } else {
//            e.preventDefault();
//            return true;
//        }
//    });
//");
?>