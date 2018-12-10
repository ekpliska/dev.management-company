<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    
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
    
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
</div>