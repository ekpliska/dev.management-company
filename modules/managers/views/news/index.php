<?php

    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;

/* 
 * Новости главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Новости';
$this->params['breadcrumbs'][] = 'Новости';
?>

<div class="manager-main-with-sub-news">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div id="_list-res">
        <?= $this->render("data/grid_{$section}", ["all_{$section}" => $results]) ?>
    </div>
    
    <?= Html::a('', ['news/create'], ['class' => 'create-request-btn']) ?>
    
</div>