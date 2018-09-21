<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Тарифы, главная
 */

$this->title = 'Тарифы';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Тариф (+)', ['rate/create'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <hr />
    
</div>