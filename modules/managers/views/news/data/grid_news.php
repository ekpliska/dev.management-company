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

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="news-item">
        <div class="news-item__title">
            <?= Html::a(FormatHelpers::shortTitleOrText($news['title'], 70), ['news/view', 'slug' => $news['slug']], ['class' => 'title']) ?>
            <p class="date"><?= FormatHelpers::formatDate($news['date'], false, 0, false) ?></p>
            
                <?php if (Yii::$app->user->can('NewsEdit')) : ?>
                    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'class' => 'btn bnt-delete-news',
                        'data-target' => '#delete_news_manager',
                        'data-toggle' => 'modal',
                        'data-news' => $news['id']]) ?>
                <?php endif; ?>
        </div>
        <div class="news-item__image">
            <span class="item__image_desc"><?= $news['rubric'] ?></span>
            <span class="item__image_desc"><?= FormatFullNameUser::nameEmployeeByUserID($news['user_id']) ?></span>
            <?= Html::img("{$news['preview']}", ['class' => 'news_preview']) ?>
        </div>
        <div class="news-item__text">
            <?= FormatHelpers::shortTextNews($news['text'], 30) ?>
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