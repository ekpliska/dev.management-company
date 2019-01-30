<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание заявки"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-new-requests',
        'header' => 'Новая заявка',
        'closeButton' => [
            'class' => 'close modal-close-btn request__btn_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-request',
        'action' => ['create-request'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-request'],
    ])
?>

    <?= $form->field($model, 'type_request', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($type_requests, [
                'prompt' => 'Выберите вид заявки из списка...'])
            ->label(false) ?>

    <?= $form->field($model, 'phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input-modal cell-phone mobile_phone'])
            ->label($model->getAttributeLabel('phone'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'flat', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($flat, [
                'id' => 'house',
                'prompt' => 'Квартира...'])
            ->label(false) ?>

    <?= $form->field($model, 'description', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('description'), ['class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Отправить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no request__btn_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>