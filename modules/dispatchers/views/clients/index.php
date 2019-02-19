<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Собственники
 */
$this->title = Yii::$app->params['site-name-dispatcher'] .  'Собственники';
$this->params['breadcrumbs'][] = 'Собственники';
?>

<?= $this->render('_form/_search', ['model' => $model]) ?>

<div class="dispatcher-main-with-sub">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
</div>