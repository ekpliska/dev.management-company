<?php

    use yii\widgets\MaskedInput;
    
/* 
 * Форма Арендатор
 */

?>

<?php if ($model_rent) : ?>

<div class="error-message"></div>

<?= $form->field($model_rent, 'rents_id', ['options' => ['class' => 'hidden']])->hiddenInput(['value' => $model_rent->rents_id, 'id' => '_rents'])->label(false) ?>
    
    <div class="field">
        <?= $form->field($model_rent, 'rents_surname')
                ->input('text', ['class' => 'field-input'])
                ->label($model_rent->getAttributeLabel('rents_surname'), ['class' => 'field-label']) ?>
    </div>
    
    <div class="field">
        <?= $form->field($model_rent, 'rents_name')
                ->input('text', ['class' => 'field-input'])
                ->label($model_rent->getAttributeLabel('rents_name'), ['class' => 'field-label']) ?>        
    </div>    

    <div class="field">    
        <?= $form->field($model_rent, 'rents_second_name')
                ->input('text', ['class' => 'field-input'])
                ->label($model_rent->getAttributeLabel('rents_second_name'), ['class' => 'field-label']) ?>
    </div>    

    <div class="field">    
        <?= $form->field($model_rent, 'rents_mobile')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', ['class' => 'field-input'])
                ->label($model_rent->getAttributeLabel('rents_mobile'), ['class' => 'field-label']) ?>
    </div>    

    <div class="field">
        <?= $form->field($model_rent, 'rents_mobile_more')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', ['class' => 'field-input'])
                ->label($model_rent->getAttributeLabel('rents_mobile_more'), ['class' => 'field-label']) ?>
    </div>
<?php endif; ?>
