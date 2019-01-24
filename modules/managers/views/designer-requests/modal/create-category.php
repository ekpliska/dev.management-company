<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Создание категории"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-category-modal',
        'header' => 'Добавить новую категорию',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-rent-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'action' => ['create-record', 'form' => 'new-category'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'new-category'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>'
        ],
    ]);
?>
    <?= $form->field($model_category, 'category_name')
            ->input('text', [
                'class' => 'field-input-modal'])
            ->label($model_category->getAttributeLabel('category_name'), [
                'class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Добавить категорию', ['class' => 'btn btn-modal-window btn-modal-window-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-modal-window btn-modal-window-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>