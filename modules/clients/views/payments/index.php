<?php
    
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
/* 
 * Палатежи и квитанции
 */

$this->title = Yii::$app->params['site-name'] . 'Палатежи и квитанции';
?>

<div class="receipts-page row">
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 receipts_period">
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
    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 receipts_body">
        <?php if (!empty($receipts_lists)) : ?>
            <iframe src="<?= Url::to(Yii::getAlias('@web') . "/receipts/{$account_number}/{$receipts_lists[0]['Расчетный период']}.pdf") ?>" style="width: 100%; height: 850px;" frameborder="0">
                Ваш браузер не поддерживает фреймы
            </iframe>
        <?php endif; ?>
    </div>
</div>