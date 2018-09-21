<?php

    use yii\helpers\Html;

/* 
 * Услуги, главная
 */

$this->title = 'Услуги';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= Html::a('Услуга (+)', ['services/create'], ['class' => 'btn btn-success']) ?>
    <?= $this->render('data/grid_services', ['services' => $services]) ?>
</div>