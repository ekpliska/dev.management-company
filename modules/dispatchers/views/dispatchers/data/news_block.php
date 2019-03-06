<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Список последних новостей для Диспетчера
 */
?>

<?php if (isset($news_lists) && !empty($news_lists)) : ?>

<?php foreach ($news_lists as $key => $post) : ?>

<div class="news-block__news-item">
    <div class="news-block__news-item__title">
        <?= Html::a($post['news_title'], ['news/view', 'slug' => $post['slug']], ['class' => 'a_title']) ?>
        <p class="date">
            <?= FormatHelpers::formatDate($post['created_at'], false, 0, false) ?>
        </p>
    </div>
    <div class="news-block__news-item__image">
        <?= Html::img("/web/{$post['news_preview']}") ?>
    </div>
    <div class="news-block__news-item__content">
        <?= FormatHelpers::shortTitleOrText($post['news_text'], 170) ?>
    </div>
</div>

<?php endforeach; ?>

<?php else: ?>
<div class="notice info">
    <p>Публикации не найдены</p>
</div>
<?php endif; ?>


