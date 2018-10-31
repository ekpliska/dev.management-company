<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
                
<?= Html::submitButton('Далее') ?>

<?php ActiveForm::end(); ?>