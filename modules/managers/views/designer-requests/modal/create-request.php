<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание заявки"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-request-modal',
        'header' => 'Добавить новую заявку',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-request-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'action' => ['create-record', 'form' => 'new-request'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-request'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>'
        ],
    ]);
?>
    <?= $form->field($model_request, 'type_requests_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model_request->getAttributeLabel('type_requests_name'), [
                'class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Добавить заявку', ['class' => 'btn btn-modal-window btn-modal-window-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-modal-window btn-modal-window-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>