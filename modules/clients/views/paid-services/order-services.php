<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = Yii::$app->params['site-name'] . 'История услуг';
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['paid-services/index']];
$this->params['breadcrumbs'][] = 'История услуг';
?>


<div class="paid-requests-history">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>  
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
</div>

<?php
$this->registerCss("
        .navbar-menu {
            box-shadow: none;
        }
");
?>

<?= ModalWindows::widget(['modal_view' => 'default_dialog']) ?>