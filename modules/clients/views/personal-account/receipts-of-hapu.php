<?php
    
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    
/* 
 * Страница "Квитанции ЖКУ"
 */

$this->title = Yii::$app->params['site-name'] . 'Квитанции КЖУ';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = 'Квитанции КЖУ';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<div class="receipts-page row">
    <div class="col-md-5 receipts_period">
        <p class="period_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
        <div class="receipts_period-calendar">
            <?= DatePicker::widget([
                    'name' => 'date_start-period',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => 'С',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-M-yyyy'
                    ]
                ]);        
            ?>
            <span>-</span>
            <?= DatePicker::widget([
                    'name' => 'date_end-period',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => 'По',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-M-yyyy'
                    ]
                ]);        
            ?>
            <?= Html::button('<i class="glyphicon glyphicon-search"></i>', ['id' => 'get-receipts', 'class' => 'btn-send-request']) ?>
        </div>
        <div id="receipts-lists">
            <?= $this->render('data/receipts-lists', [
                    'receipts_lists' => $receipts_lists,
                    'account_number' => $accoint_number,
                ]) ?>
        </div>
        
    </div>
    <div class="col-md-7 receipts_body">
        #TODO
        
    <div class="operations-block">
        <ul class="nav nav-pills operations-block_items">
            <li><a href="#" class="left-block"><i class="glyphicon glyphicon-print"></i> Распечатать</a></li>
            <li><a href="#" class="center-block"><i class="glyphicon glyphicon-ruble"></i> Оплатить</a></li>
            <li><a href="#" class="right-block"><i class="glyphicon glyphicon-send"></i> Отправить</a></li>
        </ul>
    </div>
        
    </div>
        
</div>