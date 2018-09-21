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
    
    <?= Html::a('Заявка (+)', ['requests/create'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <hr />
    
</div>