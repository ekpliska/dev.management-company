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
        'action' => ['create-record', 'model' => 'department'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-form', 'form' => 'add-department'],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>
    <?= $form->field($department_model, 'department_name')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($department_model->getAttributeLabel('department_name'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>