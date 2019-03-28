<?php

    use yii\helpers\Html;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод новостных публикаций
 */
?>

<?php if (isset($all_news) && !empty($all_news) && count($all_news) > 0) : ?>
<?php foreach ($all_news as $key => $news) : ?>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="news-item">
        <div class="news-item__title">
            <?= Html::a(FormatHelpers::shortTitleOrText($news['title'], 70), ['news/view', 'slug' => $news['slug']], ['class' => 'title']) ?>
            <p class="date"><?= FormatHelpers::formatDate($news['date'], false, 0, false) ?></p>
        </div>
        <div class="news-item__image">
            <span class="item__image_desc"><?= $news['rubric'] ?></span>
            <?= Html::img(Yii::getAlias('@web') . $news['preview'], ['class' => 'news_preview']) ?>
        </div>
        <div class="news-item__text">
            <?= FormatHelpers::shortTextNews($news['text'], 21) ?>
        </div>
    </div>
</div>

<?php endforeach; ?>
<?php else: ?>
<div class="notice info">
    <p>Публикации не найдены.</p>
</div>
<?php endif; ?>



<?= 
    LinkPager::widget([
        'pagination' => $pages,
    ]);
?>