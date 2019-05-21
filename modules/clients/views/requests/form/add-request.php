<?php

    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveForm;
    use kartik\file\FileInput;
    use yii\helpers\Html;

/* 
 * Модальное окно добавления новой заявки
 */
?>

<?php
    Modal::begin([
        'id' => 'add-request-modal',
        'header' => 'Новая заявка',
        'closeButton' => [
            'class' => 'close modal-close-btn req',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-request-modal-form',
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]);
    ?>
    
        <?= $form->field($model, 'requests_type_id', [
                    'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
                ->dropDownList($type_requests, [
                    'prompt' => 'Выберите вид заявки из списка...'])
                ->label(false) 
        ?>

        <?= $form->field($model, 'requests_comment', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
                ->label($model->getAttributeLabel('requests_comment'), ['class' => 'field-label-modal']) ?>

        <?= $form->field($model, 'gallery[]')
                ->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'maxFileCount' => 5,
                        'maxFileSize' => 20 * 1024* 1024,
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' =>  'Загрузить фотографии',
                        'resizePreference' => 'height',
                        'resizeImages' => true,
//                        'initialPreviewShowDelete' => true,
                        'uploadUrl' => ['requests/upload-image'],
                        'fileActionSettings' =>[
                            'showUpload' => false,
                        ],
                        'uploadExtraData' => [
                            'class' => $model->formName(),
                        ],
                    ]
            ])->label(false) ?>

        <div class="modal-footer">
            <?= Html::submitButton('Отправить', ['class' => 'btn-modal btn-modal-yes']) ?>
            <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no request__btn_close', 'data-dismiss' => 'modal']) ?>
        </div>    
    
    <?php ActiveForm::end(); ?>

<?php
    Modal::end();
?>