<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр/редактирование публикации
 */
$this->title = $news->news_title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
$this->params['breadcrumbs'][] = $news->news_title;
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_update', [
            'model' => $news,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'type_notice' => $type_notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
            'docs' => $docs,]) ?>
    
</div>

<?php if (!Yii::$app->user->can('CreateNewsDispatcher')) {
    $this->registerJs('
        $(":input").prop("disabled", true);
    ');
}
?>