<?php

    use kartik\date\DatePicker;
    use yii\helpers\Html;
    

/* 
 * Приборы учета
 */

$this->title = 'Приборы учета';
?>
<div class="counters-page row">

    <div class="col-md-3">
        <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
        <?= DatePicker::widget([
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('d-M-Y'),
                'layout' => '<span class="input-group-text">Birth Date</span>',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy'
                ]
            ]);        
        ?>        
    </div>
    <div class="col-md-9 text-right">
        <?= Html::button('Внести измнения', ['class' => 'btn-edit-reading']) ?>
        <?= Html::button('Сохранить', ['class' => 'btn-save-reading']) ?>
    </div>
    
    
<table class="table requests-table payment-table">
    <thead>
    <tr>
        <th></th>
        <th>Приборы учета</th>
        <th>Дата <br /> следующей поверки</th>
        <th>Дата <br /> снятия показаний</t</th>
        <th>Предыдущиее <br /> показания</t</th>
        <th>Дата снятия<br /> текущих показаний</t</th>
        <th>Текущее показание</t</th>
        <th>Расход</th>
    </tr>
    </thead>
    <tr class="block-edit-reading">
        <td>
            <?= Html::img('/images/counters/hold-water.svg', ['alt' => '']) ?>
        </td>
        <td>
            <p class="counter-name">#TODO</p>
            <p class="counter-number">#TODO</p>
        </td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>
            <span>Ввод показаний заблокирован</span>
        </td>
        <td>
            <?= Html::a('Заказать поверку', ['/'], ['class' => 'create-send-request']) ?>
        </td>
        <td>Sample text</td>
    </tr>
    <tr>
        <td>
            <?= Html::img('/images/counters/hot-water.svg', ['alt' => '']) ?>
        </td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>
            <?= Html::input('text', 'reading-input', '', ['class' => 'reading-input']) ?>
        </td>
        <td>Sample text</td>
    </tr>
    <tr>
        <td>
            <?= Html::img('/images/counters/electric-meter.svg', ['alt' => '']) ?>
        </td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
    </tr>
    <tr>
        <td>
            <?= Html::img('/images/counters/heating-meter.svg', ['alt' => '']) ?>
        </td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
    </tr>
    <tr>
        <td>
            <?= Html::img('/images/counters/heat-distributor.svg', ['alt' => '']) ?>
        </td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
    </tr>
</table>        
    
    <div class="col-md-12 counters-message">
        <p class="title">Обратите внимание</p>
        <p>#TODO</p>
    </div>
    
</div>


<?php /*
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon">Месяц</span>
            
            <?= Html::dropDownList('_list-account-all', null, ['1' => 'ЯНВ 2018'], [
                    'class' => 'form-control _list-account-all',
                    'prompt' => 'Выбрать период из списка...'
                ])
            ?>
        </div>
    </div>    
    
    <div class="col-md-12">
        <?= Html::beginForm([
            'id' => 'indication-form',
            'method' => 'POST',
        ]) ?>
            <?= $this->render('data/grid', ['counters' => $counters]) ?>
            
            <!-- Блок условных обозначений для таблицы  -->
            <br />
            <span class="glyphicon glyphicon-flash"></span> - Вы не указали показания приборов учета в текущем месяце
            <br />
        
            <div class="text-right">
                <?= Html::button('Ввести показания', ['class' => 'btn btn-primary btn__add_indication']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            
        <?= Html::endForm(); ?>
        
    </div>
    
</div>
 * 
 * 
 */ ?>

<?php
/* Фильтр заявок пользователя по 
 * ID лицевого счета, типу и статусу заявки
 */
$this->registerJs("    
    $('.current__account_list').on('change', function(e) {
        e.preventDefault();
        var account_id = $('.current__account_list').val();
        
        $.ajax({
            url: 'filter-by-account?account_id=' + account_id,
            method: 'POST',
            data: {
                account_id: account_id,
            },
            success: function(response) {
                if (response.status === false) {
                    $('.grid-counters').html('Возникла ошибка при передаче данных. Обновите страницу, нажав на клавиатуре клавишу F5');
                } else {
                    $('.grid-counters').html(response.data);
                }
            }
        });
    });
");
?>