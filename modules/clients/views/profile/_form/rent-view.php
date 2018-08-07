<?php

    use yii\bootstrap\ActiveForm;
    use yii\widgets\MaskedInput;
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<?php if ($model_rent) : ?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'edit-rent',
        ])
    ?>

        <div class="error-message"></div>
    
        <?= $form->field($model_rent, 'rents_id', ['options' => ['class' => 'hidden']])->hiddenInput(['value' => $model_rent->rents_id, 'id' => '_rents'])->label(false) ?>

        <?= $form->field($model_rent, 'rents_surname')->input('text')->label() ?>

        <?= $form->field($model_rent, 'rents_name')->input('text')->label() ?>

        <?= $form->field($model_rent, 'rents_second_name')->input('text')->label() ?>

        <?= $form->field($model_rent, 'rents_mobile')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text')->label() ?>

        <?= $form->field($model_rent, 'rents_mobile_more')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', [
                    'placeHolder' => $model_rent->getAttributeLabel('rents_mobile_more')])
                ->label() ?>
    
    <?php ActiveForm::end(); ?>
<?php else : ?>
    <p>Арендатор отсутствует.</p>
<?php endif; ?>