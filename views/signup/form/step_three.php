<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
/* 
 * Регистрация, шаг 2
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-two',
        'fieldConfig' => [
            'template' => "{label}{input}",
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin d-block my-auto material',
        ],
    ])
?>

<?= $form->errorSummary($model_step_three); ?>

<?= $form->field($model_step_three, 'phone')
        ->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99'])
        ->input('text', [
            'placeholder' => $model_step_three->getAttributeLabel('phone')])
        ->label(true) ?> 
        
<?= Html::button('Получить код', ['id' => 'send-request-to-sms']) ?>

<?= $form->field($model_step_three, 'sms_code')
        ->input('input', [
            'placeholder' => $model_step_three->getAttributeLabel('sms_code')])
        ->label(true) ?>
        
<?= Html::submitButton('Далее') ?>

<?php ActiveForm::end(); ?>