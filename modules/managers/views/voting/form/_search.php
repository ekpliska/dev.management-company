<?php

    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-manager text-center">
    <div class="row search-panel">
        <?php 
            $form = ActiveForm::begin([
                'id' => 'search-news-form',
                'action' => ['index'],
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ],
            ]); ?>
        
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
            <?= $form->field($search_model, 'value')
                    ->input('text', [
                        'class' => 'form-control _search-input', 
                        'placeHolder' => 'Заголовок опроса'])
                    ->label(false) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?= $form->field($search_model, 'voting_house_id')
                    ->dropDownList($house_lists, [
                        'prompt' => '[Адрес]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 search-panel__btn-1">
            <?= Html::submitButton('Найти', ['class' => 'btn search-block__button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>