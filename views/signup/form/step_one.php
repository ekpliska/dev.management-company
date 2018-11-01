<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-one',
        'fieldConfig' => [
            'template' => "{label}{input}",
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin d-block my-auto material',
        ],
    ])
?>

<?= $form->errorSummary($model_step_one); ?>

<?= $form->field($model_step_one, 'account_number')
    ->input('text', [
        'placeholder' => $model_step_one->getAttributeLabel('account_number')])
    ->label(true) ?>
                
<?= $form->field($model_step_one, 'last_summ')
        ->input('input', [
            'placeholder' => $model_step_one->getAttributeLabel('last_summ')])
        ->label(true) ?>
        
<?= $form->field($model_step_one, 'square')
        ->input('text', [
            'placeholder' => $model_step_one->getAttributeLabel('square')])
        ->label(true) ?>

<div class="text-center circle-btn-block mx-auto">
    <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
</div>

<?php ActiveForm::end(); ?>