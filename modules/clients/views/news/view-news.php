<?php

    use app\helpers\FormatHelpers;
    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Просмотр отдельной новости
 */
$this->title = $news['news_title'];
?>

<div class="row">
    <div class="col-3">
        <?= Html::img('@web' . $news['news_preview'], ['alt' => $news['news_title'], 'style' => 'width: 100%;']) ?>
        
        <?php if (isset($files) && count($files)) : ?>
            Прикрепленные документы:
            <?php foreach ($files as $file) : ?>
                <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
            <?php endforeach; ?>
        <?php endif; ?>
            
        <?php if ($news['isAdvert']) : ?>
            <span class="label label-danger">Реклама</span>
            <?= $news['partners_name'] ?>
        <?php endif; ?>
            
    </div>
    <div class="col-9">
        <h4 class="news-h"><?= $news['news_title']; ?></h4>
        <h5 class="news-date"><?= FormatHelpers::formatDate($news['created_at'], false) ?></h5>
        <?= $news['news_text'] ?>
    </div>
</div>