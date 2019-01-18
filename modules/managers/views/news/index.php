<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Новости главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Новости';
$this->params['breadcrumbs'][] = 'Новости';
?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?php var_dump($results) ?>
</div>