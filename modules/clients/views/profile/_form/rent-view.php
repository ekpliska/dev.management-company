<?php

    use yii\widgets\MaskedInput;
    
/* 
 * Форма Арендатор
 */

?>

<?php if ($model_rent) : ?>

    <div class="error-message"></div>
    
        <?= $form->field($model_rent, 'rents_id', ['options' => ['class' => 'hidden']])->hiddenInput(['value' => $model_rent->rents_id, 'id' => '_rents'])->label(false) ?>

        <?= $form->field($model_rent, 'rents_surname')
                ->input('text', ['placeHolder' => $model_rent->getAttributeLabel('rents_surname')])->label(false) ?>

        <?= $form->field($model_rent, 'rents_name')
                ->input('text', ['placeHolder' => $model_rent->getAttributeLabel('rents_name')])->label(false) ?>

        <?= $form->field($model_rent, 'rents_second_name')
                ->input('text', ['placeHolder' => $model_rent->getAttributeLabel('rents_second_name')])->label(false) ?>

        <?= $form->field($model_rent, 'rents_mobile')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', ['placeHolder' => $model_rent->getAttributeLabel('rents_mobile')])->label(false) ?>

        <?= $form->field($model_rent, 'rents_mobile_more')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99'])
                ->input('text', [
                    'placeHolder' => $model_rent->getAttributeLabel('rents_mobile_more')])
                ->label(false) ?>
    
<?php endif; ?>
