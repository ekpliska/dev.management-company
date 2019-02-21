<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Собственники
 */

$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = 'Собственники';
?>

<?= $this->render('_form/_search', ['model' => $model]) ?>

<div class="manager-main-with-sub _sub-2">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
</div>