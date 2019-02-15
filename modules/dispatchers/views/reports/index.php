<?php

    use yii\widgets\Breadcrumbs;
    
/*
 * Отчеты, главная страница
 */
$this->title = 'Отчеты';
$this->params['breadcrumbs'][] = 'Отчеты';
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
</div>