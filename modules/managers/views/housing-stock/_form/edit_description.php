<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Форма редактировния описания дома
 */
?>

<p class="modal-confirm-h3">
    <?= "{$model->houses_street}, дом {$model->houses_number}" ?>
</p>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-form-description',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'edit-form-description'],
    ]);
?>
    <?= $form->field($model, 'houses_description', [
                'template' => '<div class="field-modal-textarea has-label">{label}{input}{error}</div>'])
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
