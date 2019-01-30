<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание вопроса"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-question-modal',
        'header' => 'Добавить новый вопрос',
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
        'id' => 'create-question-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'action' => ['create-record', 'form' => 'new-question'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-question'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>'
        ],
    ]);
?>

    <?= $form->field($model_question, 'type_request_id')
            ->dropDownList($type_requests, [
                'prompt' => '[Заявка]'])
            ->label(false) ?>

    <?= $form->field($model_question, 'question_text')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model_question->getAttributeLabel('question_text'), [
                'class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Добавить вопрос', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>