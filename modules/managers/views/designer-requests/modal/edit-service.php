<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно формы редактирования услуги
 */
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-service-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'edit-service-form'],
        'fieldConfig' => [
            'template' => '<div class="field-modal has-label">{label}{input}{error}</div>'
        ],
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>

<div class="load_preview">
    <div class="text-center">
        <?= Html::img($model->service_image, ['id' => 'photoPreview', 'class' => 'img-rounded']) ?>
    </div>
    <div class="upload-btn-wrapper">
        <?= $form->field($model, 'service_image', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
    </div>
</div>

<div class="col-md-6">

    <?= $form->field($model, 'service_category_id')->dropDownList($categories_list)->label(false) ?>   

    <?= $form->field($model, 'service_unit_id')->dropDownList($units)->label(false) ?>   
</div>

<div class="col-md-6">
    
    <?= $form->field($model, 'service_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('service_name'), [
                'class' => 'field-label-modal']) ?>            

    <?= $form->field($model, 'service_price')
            ->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'radixPoint' => '.',
                    'autoGroup' => false,
                ]])
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('service_price'), [
                'class' => 'field-label-modal']) ?>    
</div>

<div class="col-md-12">    
    <?= $form->field($model, 'service_description', [
                'template' => '<div class="field-modal-textarea has-label">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('service_description'), ['class' => 'field-label-modal']) ?>

</div>

<div class="modal-footer">
    <?= Html::submitButton('Добавить услугу', ['class' => 'btn btn-modal-window btn-modal-window-yes']) ?>
    <?= Html::submitButton('Отмена', ['class' => 'btn btn-modal-window btn-modal-window-no', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end() ?>
