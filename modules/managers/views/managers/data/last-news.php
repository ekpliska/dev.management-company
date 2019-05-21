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
    </h5>
</div>
<div class="__content">
    <?php if (isset($news_content) && count($news_content) > 0) : ?>
        <div class="active_vote__content">
        <?php foreach ($news_content as $key => $news) : ?>
            <div class="active_vote__item">
                <div class="active_vote__item-image">
                    <?= Html::img(Yii::getAlias('@web') . $news['news_preview']) ?>
                </div>
                <div class="active_vote__item-section">
                    <?= Html::a(Html::encode($news['news_title']), ['news/view', 'slug' => $news['slug']], ['class' => 'active_vote__link-title']) ?>
                    <div class="active_vote__info">
                        <span>Автор: </span>
                        <span class="active_vote__span-info">
                            here
                        </span>
                        <span>Создано: </span>
                        <span class="active_vote__span-info">
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
        <?= Html::a('Создать публикацию', ['voting/create']) ?>
    <?php endif; ?>
</div>