<?php
    
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use app\modules\clients\widgets\SubBarNews;
    use app\helpers\FormatHelpers;

/*
 * Все новости, главная страница
 */    
$this->title = Yii::$app->params['site-name'] . "Главная";
?>


<div class="news-page">
    <?= SubBarNews::widget() ?>
    <?php if (isset($news) && count($news) > 0) : ?>
    <div class="row news-page__items">
    <?php foreach ($news as $key => $post) : ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
            <a href="<?= Url::to(['news/view', 'slug' => $post['slug']]) ?>">
                <div class="news-item">
                    <?= Html::img(Yii::getAlias('@web') . $post->news_preview, ['class' => 'news-item__preview']) ?>
                    <span class="news-item__rubric"><?= $post->rubric->rubrics_name ?></span>
                    <?php if (!empty($post->news_partner_id)) : ?>
                        <?= Html::img(Yii::getAlias('@web') . $post->partner->partners_logo, ['class' => 'news__partner-logo', 'alt' => $post->partner->partners_name]) ?>
                    <?php endif; ?>
                    <div class="news-item__info">
                        <h2 class="news-item__title">
                            <?= FormatHelpers::shortTitleOrText($post->news_title, 65) ?>
                        </h2>
                        <p class="news-item__date">
                            <?= FormatHelpers::formatDate($post->created_at, false) ?>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    </div>
    <?php else : ?>
         <div class="notice info">
            <strong>Новости</strong> по текущему разделу новостной информации не найдено.
        </div>
    <?php endif; ?>
</div>

<?= LinkPager::widget([
    'pagination' => $pages,
    ]);
?>