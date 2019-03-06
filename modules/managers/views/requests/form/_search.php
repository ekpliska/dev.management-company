<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-manager text-center">
    <div class="row search-panel">
        <?php
            $form = ActiveForm::begin([
                'id' => 'search-request-form',
                'action' => ['index'],
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ],
        ]); ?>
        
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= $form->field($search_model, 'value')
                        ->input('text', [
                            'class' => 'form-control _search-input', 
                            'placeHolder' => 'ID заявки'])
                        ->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php if (!empty($specialist_lists)) : ?>
                    <?= $form->field($search_model, 'requests_specialist_id')
                            ->dropDownList($specialist_lists, [
                                'prompt' => '[Специалист]',
                                'class' => 'form-control _dropdown-subpanel']) ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= $form->field($search_model, 'date_start')
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
                                'format' => 'yyyy-mm-dd']])
                        ->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= $form->field($search_model, 'date_finish')
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
                                'format' => 'yyyy-mm-dd']])
                        ->label(false) ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 search-panel__btn">
            <?= Html::submitButton('Найти', ['class' => 'btn search-block__button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>

