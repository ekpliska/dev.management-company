<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Новостная лента на главной
 */
?>

<?php if (isset($news) && count($news) > 0) : ?>
<h1>
    Новости
</h1>
<div class="news-carousel owl-carousel owl-theme">
<?php foreach ($news as $key => $post) : ?>
    <a href="<?= Url::to(['news/view', 'slug' => $post['slug']]) ?>">
        <div>
            <div class="news-item" data-merge="<?= $data_merge ?>">
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
    </a>
<?php endforeach; ?>
</div>
<?php endif; ?>