<?php

    use yii\helpers\Html;

/* 
 * Активные опросы
 */
?>
<div class="__title">
    <h5>
        Активные опросы
    </h5>
</div>
<div class="__content">
    <?php if (isset($active_vote) && count($active_vote) > 0) : ?>
        <div class="active_vote__content">
        <?php foreach ($active_vote as $key => $vote) : ?>
            <div class="active_vote__item">
                <div class="active_vote__item-image">
                    <?= Html::img(Yii::getAlias('@web') . '/web/' . $vote->voting_image, ['class' => 'kt-widget7__img']) ?>
                </div>
                <div class="active_vote__item-section">
                    <?= Html::a(Html::encode($vote->voting_title), ['voting/view', 'voting_id' => $vote->voting_id], ['class' => 'active_vote__link-title']) ?>
                    <div class="active_vote__info">
                        <span>Начало: </span>
                        <span class="active_vote__span-info">10.10.2019 </span>
                        <span>Завершение: </span>
                        <span class="active_vote__span-info">10.10.2019 </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= 'not found' ?>
    <?php endif; ?>
</div>

