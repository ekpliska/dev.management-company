<?php

    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use yii\widgets\ActiveForm;

/* 
 * Рендер вида редактирования заявки
 */
?>
<?php $form = ActiveForm::begin([
    'id' => 'edit-request-form',
    'enableAjaxValidation' => true,
    'validationUrl' => ['validation-form', 'form' => 'edit-request'],
]);
?>

    <?= $form->field($model, 'requests_type_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($type_requests)
            ->label(false) ?>

    <?= $form->field($model, 'requests_phone', ['template' => '<div class="field-modal has-label">{label}{input}{error}</div>'])
            ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('requests_phone'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'requests_account_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($adress_list)
            ->label(false) ?>

    <?= $form->field($model, 'requests_comment', [
                'template' => '<div class="field-modal-textarea has-label">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('requests_comment'), ['class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Готово', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>