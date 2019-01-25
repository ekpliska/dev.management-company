<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание вопроса"
 */

?>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-question-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-question'],
        'fieldConfig' => [
            'template' => '<div class="field-modal has-label">{label}{input}{error}</div>'
        ],
    ]);
?>

    <?= $form->field($model, 'type_request_id')
            ->dropDownList($type_requests, [
                'prompt' => '[Заявка]'])
            ->label(false) ?>

    <?= $form->field($model, 'question_text')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('question_text'), [
                'class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-modal-window btn-modal-window-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-modal-window btn-modal-window-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>
