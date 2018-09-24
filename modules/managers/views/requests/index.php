<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Завяки, главная
 */
$this->title = 'Заявки';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm',
        'data-target' => '#create-new-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
</div>
<?= $this->render('form/create', [
        'model' => $model,
        'service_categories' => $service_categories]) ?>
