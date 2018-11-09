<?php

    use yii\bootstrap4\Modal;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно добавления новой заявки
 */
?>

<?php
    Modal::begin([
        'id' => 'add-request-modal',
        'title' => 'Новая заявка',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req',
        ],
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-request-modal-form',
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]);
    ?>

    
        <?= $form->field($model, 'requests_type_id', ['template' => '<div class="field-modal-select">{label}{input}{error}</div>'])
                ->dropDownList($type_requests, [
                    'prompt' => 'Выберите вид заявки из списка...'])
                ->label(false) 
            ?>

        <?= $form->field($model, 'requests_phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('requests_phone'), ['class' => 'field-label-modal']) ?>

        <?= $form->field($model, 'requests_comment', ['template' => '<div class="field-modal-textarea">{label}{input}{error}</div>'])
                ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal'])
                ->label($model->getAttributeLabel('requests_comment'), ['class' => 'field-label-modal']) ?>

        <?php // = $form->field($model, 'gallery[]')->input('file', ['multiple' => true])->label(false) ?>

        <?= $form->field($model, 'gallery[]', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}</label>'])
                ->input('file', ['multiple' => true, 'id' => 'btnLoad', 'class' => 'hidden'])->label(false) ?>

        <div class="modal-footer no-border">
            <?= Html::submitButton('Отправить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 request__btn_close', 'data-dismiss' => 'modal']) ?>
        </div>    
    
    <?php ActiveForm::end(); ?>

<?php
    Modal::end();
?>