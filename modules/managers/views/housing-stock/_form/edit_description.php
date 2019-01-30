<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Форма редактировния описания дома
 */
?>

<p class="modal-confirm">
    <?= FormatHelpers::formatHousingStosk($model->houses_gis_adress, $model->houses_number) ?>
</p>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-form-description',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'edit-form-description'],
    ]);
?>
    <?= $form->field($model, 'houses_description', [
                'template' => '<div class="field-modal-textarea has-label">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('houses_description'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', [
                'class' => 'btn-modal btn-modal-yes',
            ]) ?>

        <?= Html::button('Отмена', [
                'data-dismiss' => 'modal',
                'class' => 'btn-modal btn-modal-no',
            ]) ?>
    </div>

<?php ActiveForm::end(); ?>
