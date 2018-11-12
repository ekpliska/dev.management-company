<?php

    use app\helpers\FormatHelpers;
    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Просмотр отдельной новости
 */
$this->title = $news['news_title'];
?>
<div class="new-conteiner">
    <div class="preview-news">        
        <?= Html::img('@web' . $news['news_preview'], ['alt' => $news['news_title'], 'class' => 'news-image']) ?>
        <div class="news-rubric-block">
            <span class="rubric-name"><?= $news['rubrics_name'] ?></span>
            <?php if ($news['isAdvert']) : ?>
                <span class="partner-name"><?= $news['partners_name'] ?></span>
            <?php endif; ?>
        </div>
        <div class="news-title-block">
            <h2 class="news-title"><?= $news['news_title'] ?></h2>
            <p class="news-date"><?= FormatHelpers::formatDate($news['created_at'], false) ?></p>
            
            <?php if (isset($files) && count($files)) : ?>
                <?php foreach ($files as $file) : ?>
                    <p class="load-documents">
                        <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="news-text">
        <?= $news['news_text'] ?>
    </div>
</div>