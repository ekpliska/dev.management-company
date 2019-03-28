<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\file\FileInput;

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
        <?php // = $form->field($model, 'upload_file')->input('file', ['id' => 'file'])->label() ?>
        <?= $form->field($model, 'upload_files[]')
                ->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => 'application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'maxFileCount' => 4,
                        'maxFileSize' => 10 * 1024 * 1024,
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => 'Загрузить документы'
                    ]
                ])->label(false)
        ?>
    </div>

    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', [
                'class' => 'btn-modal btn-modal-yes',
            ]) ?>

        <?= Html::button('Отмена', [
                'data-dismiss' => 'modal',
                'class' => 'btn-modal btn-modal-no',
            ]) ?>
    </div>

<?php ActiveForm::end(); ?>
