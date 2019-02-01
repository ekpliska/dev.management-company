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

            <?= $form->field($search_model, 'voting_house_id')->dropDownList($house_lists, [
                    'prompt' => '[Адрес]',
                    'class' => '_dropdown-subpanel']) ?>
            
            <?= Html::submitButton('', ['class' => 'btn search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>

