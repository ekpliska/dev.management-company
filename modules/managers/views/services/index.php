<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Услуги, главная
 */

$this->title = 'Услуги';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Услуга (+)', ['services/create'], ['class' => 'btn btn-success btn-sm']) ?>
    <?= $this->render('data/grid_services', ['services' => $services]) ?>
</div>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_service']) ?>