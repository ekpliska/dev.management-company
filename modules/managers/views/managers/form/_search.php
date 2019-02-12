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
                    'id' => 'search-managers-form',
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
            
            <?= $form->field($model, 'name')->input('text', ['class' => '_search-input', 'placeHolder' => 'Фамилия имя отчество'])->label(false) ?>
            
            <div class="form-group">
                
            <?= $form->field($model, 'employee_department_id')->dropDownList($departments, [
                    'prompt' => '[Подразделение]',
                    'class' => '_dropdown-subpanel _small']) ?>
                
            <?= $form->field($model, 'employee_posts_id')->dropDownList($posts, [
                    'prompt' => '[Должность]',
                    'class' => '_dropdown-subpanel _small']) ?>
                
            </div>
            <?= Html::submitButton('', ['class' => 'search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>