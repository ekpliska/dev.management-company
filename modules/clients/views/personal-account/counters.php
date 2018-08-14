<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use app\helpers\FormatHelpers;

/* 
 * Приборы учета
 */

$this->title = 'Приборы учета';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon">Лицевой счет</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, ['class' => 'form-control _list-account-all']) ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon">Месяц</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, ['class' => 'form-control _list-account-all']) ?>
        </div>
    </div>
    
<?php
//echo '<pre>';
//var_dump ($counters);
?>
    <div class="col-md-12">
        <?= GridView::widget([
            'dataProvider' => $counters,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                [
                    'attribute' => 'counters_type_id',
                    'label' => 'Приборы учета',
                    'encodeLabel' => false,                   
                    'value' => 'type_counters_name',
                ],
                [
                    'attribute' => 'counters_number',
                    'label' => 'Заводской номер',
                    'encodeLabel' => false,                   
                    'value' => 'counters_number',
                ],
                [
                    'attribute' => 'date_check',
                    'label' => 'Дата <br /> следующей поверки',
                    'encodeLabel' => false,                     
                    'value' => function($data) {
                        return FormatHelpers::formatDateCounter($data['date_check']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date_reading',
                    'label' => 'Дата снятия <br /> предыдущего показания',
                    'encodeLabel' => false,
                    'value' => function($data) {
                        return FormatHelpers::formatDateCounter($data['date_last']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'readings_indication',
                    'label' => 'Предыдущие <br /> показания',
                    'encodeLabel' => false,                    
                    'value' => 'ind_last'
                ],
                [
                    'attribute' => 'Дата снятия следующего показания',
                    'label' => 'Дата снятия <br /> следующего показания',
                    'encodeLabel' => false,
                    'value' => function($data) {
                        return FormatHelpers::formatDateCounter($data['date_current']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'Текущее показание',
                    'label' => 'Текущее показание',
                    'encodeLabel' => false,
                    'value' => function($data) {
                        return $data['ind_current'] ? $data['ind_current'] : yii\helpers\Html::textInput('123');
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'Расход',
                    'label' => 'Расход',
                    'encodeLabel' => false,                    
                    'value' => function($data) {
                        return $data['ind_current'] ? $data['ind_current'] - $data['ind_last'] : 'Текущие показания приборов учета не заданы';
                    },
                ],
                
            ]
        ]); ?>

    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::button('Ввести показания', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>    

</div>

<?php
$this->registerJs('
    $("input name").val("12321");
')
?>