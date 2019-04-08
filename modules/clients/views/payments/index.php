<?php
    
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    
/* 
 * Палатежи и квитанции
 */

$this->title = Yii::$app->params['site-name'] . 'Палатежи и квитанции';
?>

<div class="receipts-page row">
    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 receipts_period">
        <p class="period_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
        <div class="receipts_period-calendar">
            <?= DatePicker::widget([
                    'name' => 'date_start-period',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => 'С',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]);        
            ?>
            <span>&#45;</span>
            <?= DatePicker::widget([
                    'name' => 'date_end-period',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => 'По',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]);        
            ?>
            <?= Html::button('<i class="glyphicon glyphicon-search"></i>', [
                    'id' => 'get-receipts', 
                    'class' => 'btn-send-request',
                    'data-account-number' => $account_number,
                ]) ?>
        </div>
        <div class="message-block"></div>
        <div id="receipts-lists">
            <?= $this->render('data/receipts-lists', [
                    'receipts_lists' => $receipts_lists,
                    'account_number' => $account_number,
                ]) ?>
        </div>
        
    </div>
</div>