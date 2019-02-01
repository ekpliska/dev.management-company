<?php

    use yii\widgets\ActiveForm;
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
                    'id' => 'search-clients-form',
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
            
            <?= $form->field($model, 'input_value')->input('text', ['class' => '_search-input', 'placeHolder' => 'Фамилия имя отчество'])->label(false) ?>
            
            <?= Html::submitButton('', ['class' => 'btn search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>