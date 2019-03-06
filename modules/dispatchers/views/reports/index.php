<?php

    use yii\widgets\Breadcrumbs;
    
/*
 * Отчеты, главная страница
 */
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Отчеты';
$this->params['breadcrumbs'][] = 'Отчеты';
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
</div>