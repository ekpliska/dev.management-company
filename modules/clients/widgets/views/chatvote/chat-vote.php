<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;

/* 
 * Чат
 */
?>

<?= Html::button('Чат', ['class' => 'open-button-chat']) ?>

<div class="chat-window-open" id="chat-form">
    <?php Pjax::begin(['id' => 'comments']) ?>
<!--    <form action="/action_page.php" class="form-container">-->
        <h1>Chat</h1>
        <div class="message_window">
            <?php if (isset($chat_messages) && !empty($chat_messages)) : ?>
            <?php foreach ($chat_messages as $key => $message) : ?>
                <div class="message_window__text">
                    123
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
<!--        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="msg" required></textarea>

        <button type="submit" class="btn">Send</button>
        <button type="button" class="btn cancel">Close</button>
    </form>-->
    <?php Pjax::end() ?>

    <?php Pjax::begin(['id' => 'new_note']) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'send-message-chat',
        'action' => ['send-message', 'vote' => $vote_id],
        'validateOnChange' => false,
        'validateOnBlur' => false,
//        'fieldConfig' => [
//            'template' => '<div class="field-modal">{label}{input}</div>',
//        ],
        'options' => [
            'data-pjax' => true,
        ],
    ]) ?>
    
    <?= $form->field($model, 'message')->input('text')->label() ?>
    
    <?= Html::submitButton('Отправить') ?>
    <?= Html::button('Отмена') ?>
    
    <?php ActiveForm::end() ?>
    <?php Pjax::end() ?>

</div>

<?php
$this->registerJs("
    $(document).on('click', '.open-button-chat', function(){
        $('.chat-window-open').addClass('open-chat');
    });
    $('.cancel').on('click', function(){
        $('.chat-window-open').removeClass('open-chat');
    });
    

    $('document').ready(function(){
        $('#new_note').on('pjax:end', function() {
            $.pjax.reload({container:'#comments'});
        });
    });
");
?>
