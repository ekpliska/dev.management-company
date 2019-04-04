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
            <?= Html::img(Yii::getAlias('@web') . $post['news_preview'], ['class' => 'news-item__preview']) ?>
            <span class="news-item__rubric"><?= $post['rubric']['rubrics_name'] ?></span>
            <?php if (!empty($post['news_partner_id'])) : ?>
                <?= Html::img(Yii::getAlias('@web') . $post['partner']['partners_logo'], ['class' => 'news__partner-logo', 'alt' => $post['partner']['partners_name']]) ?>
            <?php endif; ?>
            <div class="news-item__info">
                <h2 class="news-item__title">
                    <?= FormatHelpers::shortTitleOrText($post['news_title'], 65) ?>
                </h2>
                <p class="news-item__date">
                    <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php endif;?>

<div class="clearfix"></div>

<?= LinkPager::widget(['pagination' => $pages]); ?>