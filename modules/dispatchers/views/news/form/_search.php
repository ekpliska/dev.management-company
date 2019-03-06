<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-dispatcher submenu-dispatcher__top">
    <div class="row search-panel">
        <?php 
            $form = ActiveForm::begin([
                'id' => 'search-news-form',
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ],
            ]); ?>
        
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= $form->field($model, 'news_title')
                        ->input('text', [
                            'class' => 'form-control _search-input', 
                            'placeHolder' => 'Заголовок публикации'])
                    ->label(false) ?>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <?= $form->field($model, 'news_house_id')->dropDownList($house_lists, [
                        'prompt' => '[Адрес]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?= $form->field($model, 'isAdvert')->dropDownList($type_publication, [
                        'prompt' => '[Тип публикации]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <?= $form->field($model, 'date_start')
                    ->widget(DatePicker::className(), [
                        'id' => 'date-start',
                        'language' => 'ru',
                        'options' => [
                            'placeHolder' => 'Выберите дату',
                            'class' => 'search-date',
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <?= $form->field($model, 'date_finish')
                        ->widget(DatePicker::className(), [
                            'id' => 'date-finish',
                            'language' => 'ru',
                            'options' => [
                                'placeHolder' => 'Выберите дату',
                                'class' => 'search-date',
                            ],
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]])
                        ->label(false) ?>
            </div>
            
        </div>
        
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 search-panel__btn">
            <?= Html::submitButton('Найти', ['class' => 'btn search-block__button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>

