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
        'closeButton' => false,
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false
        ],
    ]);
?>

    <h5>Для участия в голосовании введите СМС код, который был выслан на ваш номер мобильного телефонв</h5>
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
    
    <?= $form->field($model, 'number1')->input('text', ['maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number2')->input('text', ['maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number3')->input('text', ['maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number4')->input('text', ['maxlength' => 1, 'size' => 1])->label(false) ?>
    <?= $form->field($model, 'number5')->input('text', ['maxlength' => 1, 'size' => 1])->label(false) ?>
    
    <br />
    
    <?= Html::submitButton('Продолжить', ['class' => 'btn btn-primary']) ?>
    
    <?= Html::button('Отмена', [
            'data-dismiss' => 'modal', 
            'class' => 'btn btn-danger',
            'id' => 'renouncement_participate',
            'data-voting' => $voting_id,
        ]) ?>

    <?php ActiveForm::end(); ?>

<?php Modal::end() ?>
