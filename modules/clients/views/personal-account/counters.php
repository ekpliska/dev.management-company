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

    <div class="col-md-3 options-panel">
        <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Месяц и Год</p>
        <?= DatePicker::widget([
                'id' => 'date_start-period-counter',
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('F-Y'),
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'MM-yyyy'
                ]
            ]);        
        ?>        
    </div>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'send-indications-form',
            'action' => ['send-indications'],
            'validateOnChange' => false,
            'validateOnBlur' => false,
        ]);
    ?>
    
    <div class="col-md-9 text-right options-panel">
        <?php if ($is_btn) : ?>
            <?= Html::button('Внести показания', ['class' => 'btn-edit-reading']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn-save-reading', 'disabled' => true]) ?>
        <?php endif; ?>
    </div>
    
        <?= $this->render('data/grid-counters', [
                'indications' => $indications,
                'form' => $form,
                'auto_request' => $auto_request,
                'is_btn' => $is_btn,
                'model_indication' => $model_indication,
        ]) ?>

    
    <?php ActiveForm::end(); ?>
    
    
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