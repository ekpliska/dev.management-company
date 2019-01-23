<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Форма добавление характеристики выбранному дому
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'add-characteristic',
        'enableAjaxValidation' => true,
        'validationUrl' => ['edit-description-validate', 'form' => 'add-characteristic'],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>
    <div class="hidden">
        <?= $form->field($model, 'characteristics_house_id')->hiddenInput(['value' => $house_id, 'class' => 'hidden'])->label(false) ?>
    </div>    

    <?= $form->field($model, 'characteristics_name')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('characteristics_name'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'characteristics_value')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('characteristics_value'), ['class' => 'field-label-modal']) ?>

    
    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-modal-window btn-modal-window-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn btn-modal-window btn-modal-window-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>