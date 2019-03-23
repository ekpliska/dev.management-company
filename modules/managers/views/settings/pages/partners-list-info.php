<?php

    use yii\helpers\Html;

/* 
 * Справочники
 * Партнеры
 */
?>

<h4 class="title">
    Партнеры 
    <?= Html::button('', [
            'class' => 'add-item-settings pull-right',
            'data-target' => '#add-partner-modal-form',
            'data-toggle' => 'modal',
        ]) ?>
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <?php if (isset($partners) && !empty($partners)) : ?>
        <?php foreach ($partners as $key => $partner) : ?>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-3">
                <div class="partners-card">
                    <div class="partners-card__image">
                        <?= Html::img($partner->logo, ['class' => '']) ?>
                        <div class="partners-card__image__overlay">
                            <div class="overlay__text">
                                <?= Html::a('Изменить', ['edit-partner', 'partner_id' => $partner->partners_id], ['class' => 'edit-partner-btn']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="partners-card__title">
                        <?= $partner->partners_name ?>
                        <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'class' => 'delete-item-settings delete-partner-settings',
                                'data' => [
                                    'record' => $partner->partners_id,
                                    'type' => 'partner',
                                ]
                            ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>