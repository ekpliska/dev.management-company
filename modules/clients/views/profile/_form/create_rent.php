<?php

    use yii\bootstrap4\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно добавление учетной записи арендатора
 */
?>
<?php
Modal::begin([
    'id' => 'add-rent-modal',
    'title' => 'Создание учетной записи арендатора',
    'closeButton' => [
        'class' => 'close add-acc-modal-close-btn req',
    ],
]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-rent-form',
        'action' => ['create-rent-form', 
            'client' => Yii::$app->userProfile-clientID, 
            'account' => $this->context->_choosing,
        ],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-rent-form'],
    ]);
?>

<?= $form->field($add_rent, 'rents_surname')->input('text', ['placeHolder' => $add_rent->getAttributeLabel('rents_surname')])->label(false) ?>

<?= $form->field($add_rent, 'rents_name')->input('text', ['placeHolder' => $add_rent->getAttributeLabel('rents_name')])->label(false) ?>

<?= $form->field($add_rent, 'rents_second_name')->input('text', ['placeHolder' => $add_rent->getAttributeLabel('rents_second_name')])->label(false) ?>

<?= $form->field($add_rent, 'rents_mobile')
        ->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99'])
        ->input('text', [
            'placeHolder' => $add_rent->getAttributeLabel('rents_mobile')])
        ->label(false) ?>

<?= $form->field($add_rent, 'rents_email')->input('text', ['placeHolder' => $add_rent->getAttributeLabel('rents_email')])->label(false) ?>

<?= $form->field($add_rent, 'password')->input('text', ['placeHolder' => $add_rent->getAttributeLabel('password')])->label(false) ?>

<div class="modal-footer no-border">
    <?= Html::submitButton('Создать', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 request__btn_close']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>