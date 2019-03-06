<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;

/* 
 * Новости
 */
$this->title = Yii::$app->params['site-name-dispatcher'] .  'Новости';
$this->params['breadcrumbs'][] = 'Новости';
?>

<?= $this->render('form/_search', [
        'model' => $model,
        'house_lists' => $house_lists,
        'type_publication' => $type_publication,
]) ?>

<div class="dispatcher-main-with-sub">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>    
    
    <?= $this->render('data/grid', [
                'all_news' => $all_news,
                'pages' => $pages,
    ]) ?>
    
    <?php if (Yii::$app->user->can('CreateNewsDispatcher')) : ?>
        <?= Html::a('', ['news/create'], ['class' => 'create-request-btn']) ?>
    <?php endif; ?>
    
</div>