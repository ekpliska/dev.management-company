<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\ModalWindowsManager;
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
    <?= Html::button('Тариф (+)', [
        'class' => 'btn btn-sm btn-success',
        'data-target' => '#create-new-rate',
        'data-toggle' => 'modal']) ?>
    
</div>

<?= $this->render('form/create', [
        'model' => $model,
        'service_categories' => $service_categories,
        'units' => $units]) ?>
