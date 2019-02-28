<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод рекламных публикаций
 */
?>

<?php if (isset($all_adverts) && !empty($all_adverts) && count($all_adverts) > 0) : ?>
<?php foreach ($all_adverts as $key => $advert) : ?>

<div class="col-md-4">
    <div class="news-item">
        <div class="news-item__title">
            <?= Html::a($advert['title'], ['news/view', 'slug' => $advert['slug']], ['class' => 'title']) ?>
            <p class="date"><?= FormatHelpers::formatDate($advert['date'], false, 0, false) ?></p>
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                    'class' => 'btn bnt-delete-news',
                    'data-target' => '#delete_news_manager',
                    'data-toggle' => 'modal',
                    'data-news' => $advert['id']]) ?>
        </div>
        <div class="news-item__image">
            <span class="item__image_desc"><?= $advert['partners_name'] ?></span>
            <span class="item__image_desc"><?= FormatFullNameUser::nameEmployeeByUserID($advert['user_id']) ?></span>
            <?= Html::img("/web/{$advert['preview']}", ['class' => 'news_preview']) ?>
        </div>
        <div class="news-item__text">
            <?= FormatHelpers::shortTextNews($advert['text'], 40) ?>
        </div>
    </div>
</div>

<?php endforeach; ?>
<?php else: ?>
<div class="notice info">
    <p>Публикации не найдены.</p>
</div>
<?php endif; ?>