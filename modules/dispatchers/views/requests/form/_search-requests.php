<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-dispatcher">
    <div class="row search-panel">
        
        <?php
            $form = ActiveForm::begin([
                'id' => 'search-request-form',
                'action' => ['index', 'block' => 'requests'],
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ],  
        ]); ?>
            
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <?= $form->field($search_model, 'value')
                        ->input('text', [
                            'class' => 'form-control _search-input', 
                            'placeHolder' => 'ID заявки/Лицевой счет'])
                        ->label(false) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <?= $form->field($search_model, 'requests_type_id')->dropDownList($type_requests, [
                        'prompt' => '[Вид заявки]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <?= $form->field($search_model, 'requests_specialist_id')->dropDownList($specialist_lists, [
                        'prompt' => '[Специалист]',
                        'class' => 'form-control _dropdown-subpanel _small']) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <?= $form->field($search_model, 'status')->dropDownList($status_list, [
                        'prompt' => '[Статус]',
                        'class' => 'form-control _dropdown-subpanel _small']) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <?= $form->field($search_model, 'date_start')
                        ->widget(DatePicker::className(), [
                            'id' => 'date-start',
                            'language' => 'ru',
                            'options' => [
                                'placeHolder' => 'Выберите дату с',
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
                <?= $form->field($search_model, 'date_finish')
                        ->widget(DatePicker::className(), [
                            'id' => 'date-finish',
                            'language' => 'ru',
                            'options' => [
                                'placeHolder' => 'Выберите дату по',
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

