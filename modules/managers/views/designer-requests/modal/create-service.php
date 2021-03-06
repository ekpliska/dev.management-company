<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно "Создание услуги"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-service-modal',
        'header' => 'Добавить новую услугу',
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
        'id' => 'create-service-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'action' => ['create-record', 'form' => 'new-service'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-service'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>'
        ],
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>

<div class="load_preview">
    <div class="text-center">
        <?= Html::img($model_service->service_image, ['id' => 'photoPreview', 'class' => 'img-rounded']) ?>
    </div>
    <div class="upload-btn-wrapper">
        <?= $form->field($model_service, 'service_image', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                ->input('file', ['id' => 'btnLoad', 'class' => 'hidden', 'accept' => 'image/*'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
    </div>
</div>

<div class="col-md-6">

    <?= $form->field($model_service, 'service_category')
            ->dropDownList($categories_list, [
                'prompt' => '[Категория]'])
            ->label(false) ?>   

    <?= $form->field($model_service, 'service_unit')
            ->dropDownList($units, [
                'prompt' => '[Единицы измерения]'])
            ->label(false) ?>   
</div>

<div class="col-md-6">
    
    <?= $form->field($model_service, 'service_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model_service->getAttributeLabel('service_name'), [
                'class' => 'field-label-modal']) ?>            

    <?= $form->field($model_service, 'service_price')
            ->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'radixPoint' => '.',
                    'autoGroup' => false,
                ]])
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model_service->getAttributeLabel('service_price'), [
                'class' => 'field-label-modal']) ?>    
</div>

<div class="col-md-12">    
    <?= $form->field($model_service, 'service_description', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model_service->getAttributeLabel('service_description'), ['class' => 'field-label-modal']) ?>

</div>

<div class="modal-footer">
    <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
    <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>