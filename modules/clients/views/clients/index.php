<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;    

/*
 * Главная страница личного кабинета Собственника
 */    
$this->title ="Главная";
?>

<div class="row news-lists">
    <?php if (isset($news) && count($news) > 0) : ?>
    <?php foreach ($news as $key => $post) : ?>
        <div class="col-md-4">
            <div class="news-card-preview">
                <?= FormatHelpers::previewNewsOrVote($post['news_preview'], false) ?>

                <h5 class="news-card-preview-title">
                    <?= FormatHelpers::formatUrlNewsOrVote($post['news_title'], $post['slug']) ?>
                </h5>

                <h5 class="news-card-preview-date">
                    <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                </h5>

                <div class="news-card-preview-body">
                    <p class="card-text news-card-preview-text ">
                        <?= FormatHelpers::shortTextNews($post['news_text'], 20) ?>
                    </p>
                </div>
            </div>
            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php else : ?>
         <div class="notice notice-info">
            <strong>Новости</strong> по текущему разделу новостной информации не найдено.
        </div>
    <?php endif; ?>
</div>