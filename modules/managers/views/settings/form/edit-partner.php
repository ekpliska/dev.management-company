<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Модальное окно для редактирования партнера
 */
?>

<?php 
    $form = ActiveForm::begin([
        'id' => 'edit-partner',
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validationUrl' => ['validate-form', 'form' => 'add-partner'],
        'fieldConfig' => [
            'template' => '<div class="field-modal has-label">{label}{input}{error}</div>',
        ],
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>

    <div class="load_preview">
        <div class="text-center">
            <?= Html::img($model->logo, ['id' => 'photoPreview', 'class' => 'img-rounded']) ?>
        </div>
        <div class="upload-btn-wrapper">
            <?= $form->field($model, 'partners_logo', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                    ->input('file', ['id' => 'btnLoad-edit', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'partners_name')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('partners_name'), ['class' => 'field-label-modal']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'partners_adress')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('partners_adress'), ['class' => 'field-label-modal']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'partners_phone')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('partners_phone'), ['class' => 'field-label-modal']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'partners_email')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('partners_email'), ['class' => 'field-label-modal']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'partners_site')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('partners_site'), ['class' => 'field-label-modal']) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'description')
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('description'), ['class' => 'field-label-modal']) ?>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>