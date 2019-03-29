<?php

    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use yii\helpers\Html;

/* 
 * Новостная лента на главной
 */
?>

<?php if (isset($news) && count($news) > 0) : ?>
<?php foreach ($news as $key => $post) : ?>
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
        <div class="news-item">
            <?= Html::img('/web/' . $post['news_preview']) ?>
            <span class="news-item__rubric"><?= $post['rubric']['rubrics_name'] ?></span>
            <p class="news-item__title">
                <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                <?= FormatHelpers::shortTextNews($post['news_text'], 17) ?>
            </p>
        </div>
    </div>
<?php endforeach; ?>
<?php endif;?>

<div class="clearfix"></div>

<?= LinkPager::widget(['pagination' => $pages]); ?>