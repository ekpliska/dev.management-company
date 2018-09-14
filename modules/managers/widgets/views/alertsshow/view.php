<?php

    use kartik\alert\Alert;

/*
 * Вид вывода сообщений для пользователей
 */    
?>

<?php if (Yii::$app->session->hasFlash('profile')) : ?>
<?php $flash = Yii::$app->session->getFlash('profile'); ?>
    <?=
        Alert::widget([
            'type' => $flash['success'] ? Alert::TYPE_INFO : Alert::TYPE_DANGER,
            'title' => $flash['title'],
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => $flash['success'] ? $flash['message'] : $flash['error'],
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>