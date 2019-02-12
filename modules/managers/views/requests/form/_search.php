<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-request-form',
                    'action' => ['index'],
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>
            
            <?= $form->field($search_model, 'value')->input('text', ['class' => '_search-input', 'placeHolder' => 'ID заявки'])->label(false) ?>
            
                
            <?= $form->field($search_model, 'requests_type_id')->dropDownList($type_requests, [
                    'prompt' => '[Вид заявки]',
                    'class' => '_dropdown-subpanel _small']) ?>
                
            <?= $form->field($search_model, 'requests_specialist_id')->dropDownList($specialist_lists, [
                    'prompt' => '[Специалист]',
                    'class' => '_dropdown-subpanel _small']) ?>
            
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
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
            
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
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
            
            <?= Html::submitButton('', ['class' => 'search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>

