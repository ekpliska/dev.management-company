<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Форма поиска
 */
$action = Yii::$app->controller->action->id;
?>

<div class="container-fluid submenu-manager text-center _sub-double">
    <div class="row search-panel">
        <?php
            $form = ActiveForm::begin([
                'id' => 'search-dispatchers-form',
                'action' => ["$action"],
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '{input}',
                ]
            ]); ?>
        
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?= $form->field($model, 'name')
                        ->input('text', [
                            'class' => 'form-control _search-input', 
                            'placeHolder' => 'Фамилия имя отчество'])
                        ->label(false) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <?= $form->field($model, 'employee_department_id')->dropDownList($departments, [
                        'prompt' => '[Все подразделения]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <?= $form->field($model, 'employee_posts_id')->dropDownList($posts, [
                        'prompt' => '[Все должности]',
                        'class' => 'form-control _dropdown-subpanel']) ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 search-panel__btn-1">
            <?= Html::submitButton('Найти', ['class' => 'btn search-block__button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>