<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Собственники
 */

$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = 'Собственники';
?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
</div>