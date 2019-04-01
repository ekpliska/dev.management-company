<?php

    use yii\widgets\LinkPager;
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
                <div class="news-card-preview__image">
                    <?= FormatHelpers::previewNewsOrVote($post['news_preview'], false) ?>
                </div>

                <h5 class="news-card-preview-title">
                    <?= FormatHelpers::formatUrlNewsOrVote(FormatHelpers::shortTitleOrText($post['news_title'], 45), $post['slug']) ?>
                </h5>

                <h5 class="news-card-preview-date">
                    <?= FormatHelpers::formatDate($post['created_at'], true, 1) ?>
                </h5>

                <div class="news-card-preview-body">
                    <p class="card-text news-card-preview-text ">
                        <?= FormatHelpers::shortTextNews($post['news_text'], 17) ?>
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

<?= 
    isset($pages) && !empty($pages) ? LinkPager::widget([
        'pagination' => $pages,
    ]) : '';
?>