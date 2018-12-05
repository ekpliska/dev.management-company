<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
/* 
 * Форма в модальном окне, создание новой заявки на платную услугу
 */
?>
<?php
    Modal::begin([
        'id' => 'add-record-modal',
        'header' => 'Заявка на платную услугу',
        'closeButton' => [
            'class' => 'close modal-close-btn btn__paid_service_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-paid-service',
            'validateOnChange' => false,
            'validateOnBlur' => false,
        ]);
    ?>
    
    <?= $form->field($new_order, 'services_category_services_id')
            ->hiddenInput(['id' => 'secret-cat', 'value' => ''])
            ->label(false) ?>
    
    <?= $form->field($new_order, 'services_name_services_id')
            ->hiddenInput(['id' => 'secret-name', 'value' => ''])
            ->label(false) ?>
                    
    <?= $form->field($new_order, 'services_name_services_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($name_services_array, [
                'id' => 'name_services',
                'class' => 'form-control name_services',
                'disabled' => true])
            ->label(false) ?>

    <?= $form->field($new_order, 'services_phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input-modal phone cell-phone'])
            ->label($new_order->getAttributeLabel('services_phone'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($new_order, 'services_comment', [
            'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea([
                'rows' => 10,
                'class' => 'field-input-textarea-modal comment'])
            ->label($new_order->getAttributeLabel('services_comment'), ['class' => 'field-label-modal']) ?>
    
    <div class="modal-footer no-border">
        <?= Html::submitButton('Добавить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 btn__paid_service_close', 'data-dismiss' => 'modal']) ?>
    </div>
    
    <?php ActiveForm::end() ?>    
    
<?php Modal::end(); ?>