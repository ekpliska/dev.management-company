<?php

    use yii\helpers\Html;
    
/* 
 * Страница "Квитанции ЖКУ"
 */

$this->title = 'Квитанции ЖКУ'
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon">Лицевой счет</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, ['class' => 'form-control _list-account-all']) ?>
        </div>
    </div>
    
    <div class="col-md-6 text-right">
        <div class="input-group">
            <span class="input-group-addon">Месяц</span>
            <?= Html::dropDownList('_list-account-all', null, $account_all, ['class' => 'form-control _list-account-all']) ?>
        </div>
    </div>
    
    <div class="col-md-12">
        receipts
    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::button('Отправить', ['class' => 'btn btn-success']) ?>
        <?= Html::button('Распечатать', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Оплатить', ['payment'], ['class' => 'btn btn-danger']) ?>
        <?= Html::button('Загрузить', ['class' => 'btn btn-success']) ?>
    </div>
    
</div>