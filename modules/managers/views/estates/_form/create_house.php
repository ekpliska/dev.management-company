<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * ФОрма создание нового дома
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-housing-object',
    ]);
?>

<legend>Жилой комплекс</legend>

<div class="col-md-6">
    <h4>Существующий жилой комплекс</h4>
    
    <?= $form->field($model->estate, 'estate_name_drp')
            ->dropDownList($estates_list, [
                'prompt' => 'Выбрать из списка...',])
            ->label() ?>
    
</div>

<div class="col-md-6">
    <h4>Новый жилой комплекс</h4>

    <?= $form->field($model->estate, 'is_new')->checkbox()->label(false) ?>
    
    <?= $form->field($model->estate, 'estate_name')->input('text')->label() ?>
    <?= $form->field($model->estate, 'estate_town')->input('text')->label() ?>
    
</div>

<div class="col-md-6">
    <legend>Дом</legend>
    <div class="col-md-12">

            <?= $form->field($model->house, 'houses_street')->input('text')->label() ?>
            <?= $form->field($model->house, 'houses_number_house')->input('text')->label() ?>
            <?= $form->field($model->house, 'houses_description')->textarea()->label() ?>

    </div>
</div>

<div class="col-md-6">
    <legend>
        Характеристики
        <a href="#characteristics" class="btn btn-primary btn-sm" data-toggle="collapse">
            <span class="glyphicon glyphicon-menu-down"></span>
        </a>
    </legend>
    <div id="characteristics" class="collapse">
        <?= Html::button('Добавить характеристику', ['class' => 'btn btn-link']) ?>
    </div>

    <br/>
    <br/>
    
    <legend>
        Вложения
        <a href="#upload-files" class="btn btn-primary btn-sm" data-toggle="collapse">
            <span class="glyphicon glyphicon-menu-down"></span>
        </a>    
    </legend>
    <div id="upload-files" class="collapse">
        <?= Html::button('Добавить вложение', ['class' => 'btn btn-link']) ?>
    </div>
</div>

<div class="col-md-12 text-right">
<?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>