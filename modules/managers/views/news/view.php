<?php

    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр/редактирование публикации
 */
$this->title = $news->news_title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
$this->params['breadcrumbs'][] = $news->news_title;
?>

<div class="manager-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
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

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <p class="label label-warning">
        <?= FormatHelpers::formatDate($news->created_at) ?>
    </p>
    <br />
    Последний раз редактировалось: <p class="label label-success">
        <?= FormatHelpers::formatDate($news->updated_at, true) ?>
    </p>
    <br />
    Автор: <?php // = FormatFullNameUser::nameEmployerByUserID($news->news_user_id) ?>
    
    <hr />
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
    
</div> */ ?>