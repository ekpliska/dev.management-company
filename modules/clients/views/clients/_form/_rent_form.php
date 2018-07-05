<?php 
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'add_rent',
    ])
?>
    <?= $form->field($rent, 'surnamne')->input('text', ['placeHolder' => $rent->getAttributeLabel('surnamne')])->label(true) ?>
                
    <?= $form->field($rent, 'name')->input('text', ['placeHolder' => $rent->getAttributeLabel('name')])->label(true) ?>
                
    <?= $form->field($rent, 'secondname')->input('text', ['placeHolder' => $rent->getAttributeLabel('secondname')])->label(true) ?>
                
    <?= $form->field($rent, 'mobile')
            ->widget(MaskedInput::className(), [
                'mask' => '+7(999) 999-99-99'])
            ->input('text', ['placeHolder' => $rent->getAttributeLabel('mobile')])->label(true) ?>
                
    <?= $form->field($rent, 'email')->input('text', ['placeHolder' => $rent->getAttributeLabel('email')])->label(true) ?>
                
    <?= $form->field($rent, 'password')->input('password', ['placeHolder' => $rent->getAttributeLabel('password')])->label(true) ?>

<?php ActiveForm::end() ?>