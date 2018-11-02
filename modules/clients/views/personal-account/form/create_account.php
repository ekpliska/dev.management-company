<?php

    use yii\bootstrap4\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно на доабавление лицевого счета
 */
?>
<?php
    Modal::begin([
        'id' => 'create-account-modal',
        'title' => 'Добавить лицевой счет',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req account-create__btn_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-account-modal-form',
        'action' => ['create-account', 
            'client_id' => Yii::$app->userProfile-clientID, 
        ],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-account-form'],
    ]);
?>

<?= $form->field($model, 'account_number')->input('text', ['placeHolder' => $model->getAttributeLabel('account_number')])->label(false) ?>
<?= $form->field($model, 'last_sum')->input('text', ['placeHolder' => $model->getAttributeLabel('last_sum')])->label(false) ?>
<?= $form->field($model, 'square')->input('text', ['placeHolder' => $model->getAttributeLabel('square')])->label(false) ?>

<div class="modal-footer no-border">
    <?= Html::submitButton('Создать', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 account-create__btn_close', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>