<?php

    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Форма поиска
 */
?>
<div class="container-fluid submenu-manager text-center news-margin">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-news-form',
                    'action' => ['index', 'section' => $section],
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>

           
            <?= $form->field($search_model, 'value')->input('text', ['class' => '_search-input', 'placeHolder' => 'Заголовок публикации'])->label(false) ?>
            
            <?= $form->field($search_model, 'news_house_id')->dropDownList($house_lists, [
                    'prompt' => '[Адрес]',
                    'class' => '_dropdown-subpanel _large']) ?>
            
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

