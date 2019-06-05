<?php

    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use yii\widgets\ActiveForm;

/* 
 * Рендер вида редактирования заявки на платную услугу
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-request',
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'edit-paid-request'],
    ])
?>

    <?= $form->field($model, 'services_servise_category_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($servise_category, [
                'prompt' => '[Выбрать категорию услуги]',
                'id' => 'category_service'])
            ->label(false) ?>

    <?= $form->field($model, 'services_name_services_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($servise_name, [
                'id' => 'service_name'])
            ->label(false) ?>

    <?= $form->field($model, 'services_phone', ['template' => '<div class="field-modal has-label">{label}{input}{error}</div>'])
            ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
            ->input('text', ['class' => 'field-input-modal cell-phone mobile_phone'])
            ->label($model->getAttributeLabel('services_phone'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'services_account_id', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($adress_list, [
                'id' => 'house'])
            ->label(false) ?>

    <?= $form->field($model, 'services_comment', [
                'template' => '<div class="field-modal-textarea has-label">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('services_comment'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Отправить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no request__btn_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

