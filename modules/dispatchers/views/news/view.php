<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Просмотр/редактирование публикации
 */
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

<?= ModalWindowsManager::widget(['modal_view' => 'delete_news']) ?>