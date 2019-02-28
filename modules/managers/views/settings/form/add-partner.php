<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;

/* 
 * Модальное окно для добавления нового партнера
 */
?>

<?php
    Modal::begin([
        'id' => 'add-partner-modal-form',
        'header' => 'Добавить партнера',
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
        'id' => 'add-partner',
        'action' => ['create-record', 'model' => 'partner'],
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validationUrl' => ['validate-form', 'form' => 'add-partner'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>

    <?= $form->field($model, 'partners_name')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('partners_name'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'partners_adress')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('partners_adress'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>