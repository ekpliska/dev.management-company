<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;

/* 
 * Модальное окно для добавления нового подразделения
 */
?>

<?php
    Modal::begin([
        'id' => 'add-department-modal-form',
        'header' => 'Добавить подразделения',
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
        'id' => 'add-department',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'add-department'],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>
    <?php /* = $form->field($model, 'characteristics_name')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('characteristics_name'), ['class' => 'field-label-modal']) */ ?>

    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>