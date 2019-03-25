<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;

/* 
 * Чат
 */
?>

<?= Html::button('<i class="glyphicon glyphicon-comment"></i>', ['class' => 'open-button-chat']) ?>

<div class="chat-window-open" id="chat-form">
    <?php Pjax::begin(['id' => 'comments']) ?>
    <div class="row chat-window-open__body">
        <?php if (isset($chat_messages) && !empty($chat_messages)) : ?>
            <?php foreach ($chat_messages as $key => $message) : ?>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 chat_photo">
                    <?= Html::img(Yii::getAlias('@web') . $message->user->photo, ['class' => 'request-chat-icon']) ?>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 chat-txt-block-white">
                    <div class="chat-txt-block-white__atr">
                        <p class="chat-name-user"><?= $message->user->user_login ?></p>
                        <p class="chat-send-date"><?= $message->created_at ?></p>
                    </div>
                    <?= $message->chat_message ?>
                </div>
                <div class="clearfix"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Чтобы начать беседу, отправьте сообщение!</p>
        <?php endif; ?>
    </div>
    
    <?php Pjax::end() ?>
    
    <div class="clearfix"></div>
    
    <?php Pjax::begin(['id' => 'new_note']) ?>
    <div class="chat-window-open__form text-center">
        <?php $form = ActiveForm::begin([
            'id' => 'send-message-chat',
            'action' => ['send-message', 'vote' => $vote_id],
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'options' => [
                'data-pjax' => true,
            ],
        ]) ?>

        <?= $form->field($model, 'message')->textarea(['class' => 'chat-message__input', 'rows' => 3])->label() ?>
        <?= Html::submitButton('<i class="glyphicon glyphicon-send"></i>', ['class' => 'chat-window-open__sendbtn']) ?>

        <?php ActiveForm::end() ?>
    </div>
    <?php Pjax::end() ?>

</div>

<?php
$this->registerJs("
    $(document).on('click', '.open-button-chat', function(){
        $('.chat-window-open').toggleClass('open-chat');
    });

    $('document').ready(function(){
        $('#new_note').on('pjax:end', function() {
            $.pjax.reload({container:'#comments'});
        });
    });
");
?>
