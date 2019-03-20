<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Пользовательское соглашение
 */
?>

<?php
Modal::begin([
    'id' => 'user_agreement',
    'header' => 'Пользовательское соглашение',
    'size' => Modal::SIZE_LARGE,
    'closeButton' => [
        'class' => 'close close modal-close-btn',
    ],
]);
?>

    <div class="user_agreement__body">
        <?php if (!empty($user_agreement)) : ?>
        <p>
            <?= $user_agreement['user_agreement'] ?>
        </p>
        <?php endif; ?>
    </div>

    <div class="modal-footer">
        <?= Html::button('Закрыть', [
                'data-dismiss' => 'modal', 
                'class' => 'btn-modal btn-modal-no',
        ]) ?>
    </div>

<?php Modal::end(); ?>

