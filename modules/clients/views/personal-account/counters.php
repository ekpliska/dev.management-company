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

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Лицевой счет</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, [
                    'class' => 'form-control _list-account-all',
                    'prompt' => 'Выбрать лицевой счет из списка...',
                ]) 
            ?>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Год</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, [
                    'class' => 'form-control _list-account-all',
                    'prompt' => 'Выбрать период из списка...'
                ]) 
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Месяц</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, [
                    'class' => 'form-control _list-account-all',
                    'prompt' => 'Выбрать период из списка...'
                ]) 
            ?>
        </div>
    </div>    
    
    <div class="col-md-3">
        <div class="input-group">
            <?= Html::button('Поиск', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    
<?php
//echo '<pre>';
//var_dump (time());
?>
    
    <div class="col-md-12">
        <?= GridView::widget([
            'dataProvider' => $counters,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                [
                    'attribute' => 'type_counters_name',
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
                    'value' => function($data) use ($current_date) {
            
                        if ($data['date_check'] < $current_date) {
                            $value = '<span style="color: red">' . FormatHelpers::formatDateCounter($data['date_check']) . '</span>';
                            $value .= '<br />' . Html::a('Заказать поверку', ['/']);
                        } else {
                            $value = '<span>' . FormatHelpers::formatDateCounter($data['date_check']) . '</span>';
                        }
                        
                        return $value;
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date_reading',
                    'label' => 'Дата снятия <br /> предыдущего показания',
                    'encodeLabel' => false,
                    'value' => function($data) {
                        return FormatHelpers::formatDateCounter($data['last_date']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'readings_indication',
                    'label' => 'Предыдущие <br /> показания',
                    'encodeLabel' => false,                    
                    'value' => 'last_ind'
                ],
                [
                    'attribute' => 'Дата снятия следующего показания',
                    'label' => 'Дата снятия <br /> следующего показания',
                    'encodeLabel' => false,
                    'value' => function($data) {
                        return FormatHelpers::formatDateCounter($data['current_date']);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'Текущее показание',
                    'label' => 'Текущее показание',
                    'encodeLabel' => false,
                    'value' => function($data) use ($current_date) {
                        
                        if ($data['date_check'] < $current_date) {
                            return '<span style="color: red">' . 'Ввод показаний ЗАБЛОКИРОВАН' . '</span><br />' . Html::a('Что делать?', ['/']);
                        } else 
                            if ($data['current_ind']) {
                                return Html::textInput('curr_indication', $data['current_ind'], [
                                    'class' => 'form-control indication_val',
                                    'dir' => 'rtl']) 
                                    . $data['current_ind'];
                            } else {
                                return Html::textInput('curr_indication', null, [
                                    'class' => 'form-control indication_val',
                                    'dir' => 'rtl']);
                            }
                        },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'Расход',
                    'label' => 'Расход',
                    'encodeLabel' => false,                    
                    'value' => function($data) {
                        return $data['current_ind'] ? $data['current_ind'] - $data['last_ind'] : '<span class="glyphicon glyphicon-flash"></span>';
                    },
                    'format' => 'raw',
                ],
                
            ]
        ]); ?>

    </div>
    
    
    <!-- Блок условных обозначений для таблицы  -->
    <div class="col-md-12">
        <br />        
        <span class="glyphicon glyphicon-flash"></span> - Вы не указали показания приборов учета в текущем месяце
        <br />
    </div>    
    
    <div class="col-md-12 text-right">
        <?= Html::button('Ввести показания', ['class' => 'btn btn-primary btn__add_indication']) ?>
        <?= Html::button('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>    

</div>

<?php
$this->registerJs('
    $("input name").val("12321");
')
?>