<?php

    use app\helpers\FormatHelpers;

/*
 * Главная страница личного кабинета Собственника
 */    
$this->title = Yii::$app->params['site-name'] . "Главная";
?>

<div class="row news-lists">
    <?php if (isset($news) && count($news) > 0) : ?>
    <?php foreach ($news as $key => $post) : ?>
        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
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
        </div>
    <?php endforeach; ?>
    <?php else : ?>
         <div class="notice info">
            <strong>Новости</strong> по текущему разделу новостной информации не найдено.
        </div>
    <?php endif; ?>
</div>