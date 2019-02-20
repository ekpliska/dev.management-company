<?php

    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
/* 
 * Форма в модальном окне, создание новой заявки на платную услугу
 */
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'add-paid-service',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>

    <p class="modal-note-info">
        <?= $category['category_name'] ?>
        <span><?= $service['service_name'] ?></span>
    </p>

    <div class="hidden">
    <?= $form->field($new_order, 'services_servise_category_id')
            ->hiddenInput(['id' => 'secret-cat', 'value' => $category['category_id']])
            ->label(false) ?>
    
    <?= $form->field($new_order, 'services_name_services_id')
            ->hiddenInput(['id' => 'secret-name', 'value' => $service['service_id']])
            ->label(false) ?>
    </div>
    
    <?= $form->field($new_order, 'services_comment', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
                ->label($new_order->getAttributeLabel('services_comment'), ['class' => 'field-label-modal']) ?>
    
    <div class="modal-footer no-border">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no btn__paid_service_close', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php ActiveForm::end() ?>    
    