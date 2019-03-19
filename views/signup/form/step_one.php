<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    
/* 
 * Форма регистрации, шаг первый
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-one',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field">{label}{input}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin',
        ],
    ])
?>

<?= $form->errorSummary($model_step_one, ['header' => '']); ?>

<?= $form->field($model_step_one, 'account_number')
        ->input('text', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('account_number'), ['class' => 'field-label']) ?>
                
<?= $form->field($model_step_one, 'last_summ')
        ->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'radixPoint' => '.',
                    'autoGroup' => false]])
        ->input('input', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('last_summ'), ['class' => 'field-label']) ?>
        
<?= $form->field($model_step_one, 'square')
        ->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'groupSeparator' => '',
                    'radixPoint' => '.',
                    'autoGroup' => false]])
        ->input('input', ['class' => 'field-input'])
        ->input('text', ['class' => 'field-input'])
        ->label($model_step_one->getAttributeLabel('square'), ['class' => 'field-label']) ?>

<div class="text-center circle-btn-block">
    <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
</div>

<?php ActiveForm::end(); ?>