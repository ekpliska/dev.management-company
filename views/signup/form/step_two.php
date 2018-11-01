<?php

    use yii\widgets\ActiveForm;
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

<?= $form->errorSummary($model_step_two); ?>

<?= $form->field($model_step_two, 'email')
    ->input('text', [
        'placeholder' => $model_step_two->getAttributeLabel('email')])
    ->label(true) ?>
                
<?= $form->field($model_step_two, 'password')
        ->input('input', [
            'placeholder' => $model_step_two->getAttributeLabel('password')])
        ->label(true) ?>
        
<?= $form->field($model_step_two, 'password_repeat')
        ->input('text', [
            'placeholder' => $model_step_two->getAttributeLabel('password_repeat')])
        ->label(true) ?>
                
<div class="text-center circle-btn-block mx-auto">
    <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
</div>

<?php ActiveForm::end(); ?>