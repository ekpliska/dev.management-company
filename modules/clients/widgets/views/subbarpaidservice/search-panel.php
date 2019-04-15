<?php

    use yii\widgets\ActiveForm;

/* 
 * Вид дополнительного навигационного меню для страниц 
 *      История платных услуг
 */
?>

<div class="row navbar_paid-request text-right">        
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    <?= $form->field($_search, '_input')
            ->input('text', [
                'placeHolder' => 'Поиск',
                'id' => '_search-input',
                'class' => 'search-block__input-dark'])
            ->label(false) ?>

    <?php ActiveForm::end(); ?>
</div>
