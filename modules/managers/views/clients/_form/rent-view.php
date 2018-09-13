<?php

    use yii\widgets\MaskedInput;

/* 
 * Данные арендатора
 */
?>
<?= $form->field($rent_info, 'rents_surname')
        ->input('text', [
            'placeHolder' => $rent_info->getAttributeLabel('rents_surname')])
        ->label() ?>

<?= $form->field($rent_info, 'rents_name')
        ->input('text', [
            'placeHolder' => $rent_info->getAttributeLabel('rents_name')])
        ->label() ?>

<?= $form->field($rent_info, 'rents_second_name')
        ->input('text', [
            'placeHolder' => $rent_info->getAttributeLabel('rents_second_name')])
        ->label() ?>

<?= $form->field($rent_info, 'rents_mobile')
        ->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99'])
        ->input('text', [
            'placeHolder' => $rent_info->getAttributeLabel('rents_mobile')])
        ->label() ?>

<?= $form->field($rent_info, 'rents_mobile_more')
        ->widget(MaskedInput::className(), [
            'mask' => '+7 (999) 999-99-99'])
        ->input('text', [
            'placeHolder' => $rent_info->getAttributeLabel('rents_mobile_more')])
        ->label() ?>
