<?php

    use yii\widgets\MaskedInput;

/* 
 * Данные арендатора
 */
?>
<?php if (isset($rent_info) && $rent_info) : ?>

    <?= $form->field($rent_info, 'rents_id', ['options' => ['class' => 'hidden']])
            ->hiddenInput([
                'value' => $model_rent->rents_id, 
                'id' => '_rents'])
            ->label(false) ?>

    <?= $form->field($rent_info, 'rents_surname')
            ->input('text', [
                'id' => 'rents_surname',
                'class' => 'field-input',
                'data-surname' => $rent_info->rents_surname,])
            ->label($rent_info->getAttributeLabel('rents_surname'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_name')
            ->input('text', [
                'id' => 'rents_name',
                'class' => 'field-input',
                'data-name' => $rent_info->rents_name])
            ->label($rent_info->getAttributeLabel('rents_name'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_second_name')
            ->input('text', [
                'id' => 'rents_second_name',
                'class' => 'field-input',
                'data-second-name' => $rent_info->rents_second_name])
            ->label($rent_info->getAttributeLabel('rents_second_name'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_mobile')
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (999) 999-99-99'])
            ->input('text', [
                'class' => 'field-input'])
            ->label($rent_info->getAttributeLabel('rents_mobile'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_mobile_more')
            ->widget(MaskedInput::className(), [
                'mask' => '+7 (9999) 999-99-99'])
            ->input('text', [
                'class' => 'field-input'])
            ->label($rent_info->getAttributeLabel('rents_mobile_more'), ['class' => 'field-label']) ?>
<?php else : ?>
    Арендатор отсутствует
<?php endif; ?>