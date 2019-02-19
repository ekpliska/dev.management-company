<?php


/* 
 * Данные арендатора
 */
?>
<?php if (isset($rent_info) && $rent_info) : ?>

    <?= $form->field($rent_info, 'rents_surname')
            ->input('text', ['class' => 'field-input', 'disabled' => true])
            ->label($rent_info->getAttributeLabel('rents_surname'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_name')
            ->input('text', ['class' => 'field-input', 'disabled' => true])
            ->label($rent_info->getAttributeLabel('rents_name'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_second_name')
            ->input('text', ['class' => 'field-input', 'disabled' => true])
            ->label($rent_info->getAttributeLabel('rents_second_name'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_mobile')
            ->input('text', ['class' => 'field-input', 'disabled' => true])
            ->label($rent_info->getAttributeLabel('rents_mobile'), ['class' => 'field-label']) ?>

    <?= $form->field($rent_info, 'rents_mobile_more')
            ->input('text', ['class' => 'field-input', 'disabled' => true ])
            ->label($rent_info->getAttributeLabel('rents_mobile_more'), ['class' => 'field-label']) ?>
<?php else : ?>
    Арендатор отсутствует
<?php endif; ?>