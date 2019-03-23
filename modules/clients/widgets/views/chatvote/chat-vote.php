<?php

    use yii\helpers\Html;

/* 
 * Чат
 */
?>

<?= Html::button('Чат', ['class' => 'open-button-chat']) ?>

<div class="chat-window-open" id="chat-form">
  <form action="/action_page.php" class="form-container">
    <h1>Chat</h1>

    <label for="msg"><b>Message</b></label>
    <textarea placeholder="Type message.." name="msg" required></textarea>

    <button type="submit" class="btn">Send</button>
    <button type="button" class="btn cancel">Close</button>
  </form>
</div>

<?php
$this->registerJs("
    $('.open-button-chat').on('click', function(){
        $('.chat-window-open').addClass('open-chat');
    });
    $('.cancel').on('click', function(){
        $('.chat-window-open').removeClass('open-chat');
    });
");
?>
