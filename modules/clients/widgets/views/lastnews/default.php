<?php

    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;

/* 
 * Новостная лента на главной
 */
?>

<?php if (isset($news) && count($news) > 0) : ?>
<?php foreach ($news as $key => $post) : ?>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <div class="news-item">
            <?= FormatHelpers::previewNewsOrVote($post['news_preview'], false) ?>
            <?= FormatHelpers::formatUrlNewsOrVote(FormatHelpers::shortTitleOrText($post['news_title'], 45), $post['slug']) ?>
            <?= FormatHelpers::formatDate($post['created_at'], false) ?>
            <?= FormatHelpers::shortTextNews($post['news_text'], 17) ?>
        </div>
    </div>
<?php endforeach; ?>
<?php endif;?>

<?= LinkPager::widget(['pagination' => $pages]); ?>