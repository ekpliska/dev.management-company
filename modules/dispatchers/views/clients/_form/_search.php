<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>

<div class="container-fluid submenu-dispatcher submenu-dispatcher__top">
    <div class="row search-panel">
        <?php
            $form = ActiveForm::begin([
                'id' => 'search-clients-form',
                'action' => ['index'],
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ],
            ]); ?>
            
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
            <?= $form->field($model, 'input_value')
                    ->input('text', [
                        'class' => 'form-control _search-input', 
                        'placeHolder' => 'Фамилия имя отчество'])
                    ->label(false) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 search-panel__btn-1">
            <?= Html::submitButton('Найти', ['class' => 'btn search-block__button']) ?>
        </div>
            
            
            
            
            <?php ActiveForm::end(); ?>
    </div>
</div>