<?php

    use yii\helpers\Html;
    

/* 
 * Приборы учета
 */

$this->title = 'Приборы учета';
?>
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
        <?= $this->render('data/grid', ['counters' => $counters]) ?>
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