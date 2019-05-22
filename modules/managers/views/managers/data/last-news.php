<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    
/*
 * Последний новости
 */

?>

<div class="__title">
    <h5>
        Последние новости
        <div class="dropdown pull-right settings-block">
            <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu">
                <li><?= Html::a('Создать публикацию', ['news/create']) ?></li>
                <li><?= Html::a('Все публикации', ['news/index']) ?></li>
            </ul>
        </div>
    </h5>
</div>
<div class="__content">
    <?php if (isset($news_content) && count($news_content) > 0) : ?>
        <div class="active_block__content">
        <?php foreach ($news_content as $key => $news) : ?>
            <div class="active_block__item">
                <div class="active_block__item-image">
                    <?= Html::img(Yii::getAlias('@web') . '/web/' . $news['news_preview']) ?>
                </div>
                <div class="active_block__item-section">
                    <?= Html::a(Html::encode($news['news_title']), ['news/view', 'slug' => $news['slug']], ['class' => 'active_block__link-title']) ?>
                    <div class="active_block__info">
                        <span>Опубликовано: </span>
                        <span class="active_block__span-info">
                            <?= FormatHelpers::formatDate($news['created_at'], false, 1, false) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
         <p>
            Новостной блок не содержит информации.
        </p>
        <?= Html::a('Создать публикацию', ['voting/create'], ['class' => 'active_block__link']) ?>
    <?php endif; ?>
</div>