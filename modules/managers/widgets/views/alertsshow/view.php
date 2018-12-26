<?php

    use kartik\alert\Alert;

/*
 * Вид вывода сообщений для пользователей
 */    
?>


<?php if (Yii::$app->session->hasFlash('success')) : ?>
<?php $message = Yii::$app->session->getFlash('success')['message'] ?>
    <div class="alert-message" data-notification-status="success">
        <?= $message ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')) : ?>
<?php $message = Yii::$app->session->getFlash('error')['message'] ?>
    <div class="alert-message" data-notification-status="error">
        <?= $message ?>
    </div>
<?php endif; ?>