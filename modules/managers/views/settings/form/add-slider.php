<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;

/* 
 * Модальное окно для добавления нового слайда
 * Слайдер
 */
?>

<?php
    Modal::begin([
        'id' => 'add-slider-modal-form',
        'header' => 'Добавить слайд',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'add-slider',
        'action' => ['create-record', 'model' => 'slider'],
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validationUrl' => ['validate-form', 'form' => 'add-slider'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>

    <?= $form->field($model, 'slider_title')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('slider_title'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'slider_text')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('slider_text'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'button_1')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('button_1'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'button_2')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('button_2'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>