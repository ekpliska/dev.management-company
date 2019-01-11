<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    

/* 
 * Модальное окно, подтверждения СМС кода на участие в регистрации
 */
?>


<?php 
Modal::begin([
    'id' => 'participate-in-voting-' . $voting_id,
    'header' => '',
    'closeButton' => [
        'class' => 'close close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
]);
?>

<h4 class="modal-title-vote">
    Для участия в голосовании введите СМС код, <br />
    который был выслан на ваш номер мобильного телефона
</h4>


<div class="sms-cod text-center">
    <?php 
        $form = ActiveForm::begin([
            'id' => 'fill_sms_to_participate',
            'validateOnSubmit' => false,
            'validateOnChange' => false,
            'validateOnBlur' => false,            
            'options' => [
                'class' => 'form-inline',
            ]
        ]); 
    ?>
    
    <?php // = $model->errorSummary($form, ['header' => '']); ?>
    
    <?= $form->field($model, 'number1')
            ->input('text', [
                'class' => 'number-sms', 
                'maxlength' => 1, 
                'size' => 1])
            ->label(false) ?>
    <?= $form->field($model, 'number2')
            ->input('text', [
                'class' => 'number-sms', 
                'maxlength' => 1, 
                'size' => 1])
            ->label(false) ?>
    <?= $form->field($model, 'number3')
            ->input('text', [
                'class' => 'number-sms', 
                'maxlength' => 1, 
                'size' => 1])
            ->label(false) ?>
    <?= $form->field($model, 'number4')
            ->input('text', [
                'class' => 'number-sms', 
                'maxlength' => 1, 
                'size' => 1])
            ->label(false) ?>
    <?= $form->field($model, 'number5')
            ->input('text', [
                'class' => 'number-sms', 
                'maxlength' => 1, 
                'size' => 1])
            ->label(false) ?>
                
</div>
            
<div class="text-center">
    <?= Html::button('Отправить код еще раз', [
            'class' => 'again-sms-btn',
            'id' => 'repeat_sms_code',
            'data-voting' => $voting_id,
        ]) ?>
    <br />
    <span class="repeat_sms_code-message"></span>
</div>
                
<div class="modal-footer">
    <?= Html::submitButton('Продолжить', [
            'id' => 'participate_in', 
            'class' => 'btn blue-outline-btn',
            'data-voting-id' => $voting_id,
        ]) ?>
    
    <?= Html::button('Отмена', [
            'data-dismiss' => 'modal', 
            'class' => 'btn red-outline-btn',
            'id' => 'renouncement_participate',
            'data-voting' => $voting_id,
        ]) ?>
                
</div>
            
<?php ActiveForm::end(); ?>

<?php
Modal::end();
?>