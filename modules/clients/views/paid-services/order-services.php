<?php

    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    use app\modules\clients\widgets\SubBarPaidService;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = Yii::$app->params['site-name'] . 'История услуг';
?>


<div class="paid-requests-history">
    
    <?= SubBarPaidService::widget(['view_name' => 'search-panel']) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
</div>

<?= ModalWindows::widget(['modal_view' => 'default_dialog']) ?>