<?php

    use yii\bootstrap\Modal;
    use yii\bootstrap\ActiveForm;
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

        <?= $form->field($model, 'requests_phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
                ->input('text', ['class' => 'field-input-modal cell-phone'])
                ->label($model->getAttributeLabel('requests_phone'), ['class' => 'field-label-modal']) ?>

        <?= $form->field($model, 'requests_comment', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
                ->label($model->getAttributeLabel('requests_comment'), ['class' => 'field-label-modal']) ?>

        <?= $form->field($model, 'gallery[]', [
                'template' => '<label class="modal_btn-upload" role="button"><i class="fa fa-paperclip" aria-hidden="true"></i>{input}{label}{error}</label>'])
                ->input('file', [
                    'class' => 'addImages hidden', 
                    'multiple' => true])
                ->label(false) ?>

        <div class="form__upload-image">
            <ul id="uploadImagesList">
                <li class="item template">
                    <span class="img-wrap">
                        <img src="" alt="">
                    </span>
                    <span class="delete-link" title="Удалить"><i class="fa fa-times" aria-hidden="true"></i></span>
                </li>
            </ul>
        </div>

        <div class="modal-footer">
            <?= Html::submitButton('Отправить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
            <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 request__btn_close', 'data-dismiss' => 'modal']) ?>
        </div>    
    
    <?php ActiveForm::end(); ?>

<?php
    Modal::end();
?>