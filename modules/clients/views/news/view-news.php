<?php

    use app\helpers\FormatHelpers;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;

/* 
 * Просмотр отдельной новости
 */
$this->title = Yii::$app->params['site-name'] .  $news['news_title'];
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $news['news_title'];
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<?php // var_dump($news); die(); ?>
<div class="new-conteiner">
    <div class="preview-news">
    <?= FormatHelpers::previewNewsOrVote($news['news_preview'], true) ?>
    <div class="news-rubric-block">
        <span class="rubric-name"><?= $news['rubric']['rubrics_name'] ?></span>
            <?php if ($news['isAdvert'] == 1) : ?>
                <span class="partner-name"><?= $news['partner']['partners_name'] ?></span>
            <?php endif; ?>
    </div>
    <div class="news-title-block">
        <h2 class="news-title"><?= $news['news_title'] ?></h2>
        <p class="news-date"><?= FormatHelpers::formatDate($news['created_at'], false) ?></p>
    </div>
    </div>

    <div class="news-text">
        <?= $news['news_text'] ?>
    </div>
    
    <?php if (isset($files) && count($files)) : ?>
        <div class="load-news-documents">
            <h4><span>Вложения</span></h4>
                <?php foreach ($files as $file) : ?>
                    <p class="load-documents">
                        <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
                    </p>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>