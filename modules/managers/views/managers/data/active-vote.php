<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Активные опросы
 */
?>
<div class="__title">
    <h5>
        Активные опросы
        <div class="dropdown pull-right settings-block">
            <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu">
                <li><?= Html::a('Создать опрос', ['voting/create']) ?></li>
                <li><?= Html::a('Все опросы', ['voting/index']) ?></li>
            </ul>
        </div>
    </h5>
</div>
<div class="__content">
    <?php if (isset($active_vote) && count($active_vote) > 0) : ?>
        <div class="active_block__content">
        <?php foreach ($active_vote as $key => $vote) : ?>
            <div class="active_block__item">
                <div class="active_block__item-image">
                    <?= Html::img(Yii::getAlias('@web') . $vote->voting_image) ?>
                </div>
                <div class="active_block__item-section">
                    <?= Html::a(Html::encode($vote->voting_title), ['voting/view', 'voting_id' => $vote->voting_id], ['class' => 'active_block__link-title']) ?>
                    <div class="active_block__info">
                        <span>Начало: </span>
                        <span class="active_block__span-info">
                            <?= FormatHelpers::formatDate($vote->voting_date_start, false, 1, false) ?>
                        </span>
                        <span>Завершение: </span>
                        <span class="active_block__span-info">
                            <?= FormatHelpers::formatDate($vote->voting_date_end, false, 1, false) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>
            Активных опросов не найдено.
        </p>
        <?= Html::a('Создать опрос', ['voting/create'], ['class' => 'active_block__link']) ?>
    <?php endif; ?>
</div>

