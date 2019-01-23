<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Форма загрузки документов
 */
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-load-file',
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>

    <?= $form->field($model, 'houses_id')->hiddenInput(['value' => $house_cookie, 'class' => 'hidden'])->label(false) ?>
    <div class="row">
        <?= $form->field($model, 'upload_file')->input('file', ['id' => 'file'])->label() ?>
    </div>

    <?= Html::button('Отмена', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
