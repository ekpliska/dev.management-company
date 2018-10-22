<?php

    use app\helpers\FormatHelpers;
    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Просмотр отдельной новости
 */
$this->title = $news['news_title'];
?>
<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-3">
        <?= Html::img('@web' . $news['news_preview'], ['alt' => $news['news_title'], 'style' => 'width: 100%;']) ?>
        <hr />
        <?= $news['rubrics_name'] ?>
        <hr />
        <?= FormatHelpers::formatDate($news['created_at'], false) ?>
        <hr />
        <?php if (isset($files) && count($files)) : ?>
            Прикрепленные документы:
            <?php foreach ($files as $file) : ?>
                <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr />
        <?php if ($news['isAdvert']) : ?>
            <span class="label label-danger">Реклама</span>
            <?= $news['partners_name'] ?>
        <?php endif; ?>
    </div>
    <div class="col-md-9">
        <?= $news['news_text'] ?>
    </div>
    
</div>