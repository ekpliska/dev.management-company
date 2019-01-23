<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\helpers\FormatHelpers;

/* 
 * Установка статуса "Должник"
 * Добавление примечания к установке статуса
 */
?>

<p class="modal-note-info">
    <?= "Квартира {$flat_info['flats_number']}, подъезд {$flat_info['flats_porch']}" ?>
</p>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-add-note',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'form-add-note'],
    ]);
?>

    <?= $form->field($model, 'notes_flat_id')->hiddenInput(['value' => $flat_id, 'class' => 'hidden'])->label(false) ?>


    <?= $form->field($model, 'notes_name', [
                'template' => '<div class="field-modal-textarea has-label">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal'])
                ->label($model->getAttributeLabel('notes_name'), ['class' => 'field-label-modal']) ?>


    <div class="modal-footer">
        <?= Html::submitButton('Добавить', [
                'class' => 'btn btn-modal-window btn-modal-window-yes',
            ]) ?>

        <?= Html::button('Отмена', [
                'data-dismiss' => 'modal',
                'class' => 'btn btn-modal-window btn-modal-window-no',
            ]) ?>
    </div>

<?php ActiveForm::end(); ?>