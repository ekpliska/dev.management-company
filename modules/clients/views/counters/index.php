<?php

    use kartik\date\DatePicker;
    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;

/* 
 * Приборы учета
 */

$this->title = Yii::$app->params['site-name'] . 'Показания приборов учета';
?>

<?= AlertsShow::widget() ?>

<div class="counters-page row">

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 options-panel">
        <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Месяц и Год</p>
        <?= DatePicker::widget([
                'id' => 'date_start-period-counter',
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'options' => [
                    'placeholder' => Yii::$app->formatter->asDate(time(), 'LLLL-yyyy')
                ],
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
