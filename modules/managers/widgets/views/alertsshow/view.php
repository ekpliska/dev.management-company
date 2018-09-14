<?php

    use kartik\alert\Alert;

/*
 * Вид вывода сообщений для пользователей
 */    
?>

<?php if (Yii::$app->session->hasFlash('profile-admin')) : ?>
    <?=
        Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Профиль пользователя',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => 'Данные профиля были успешно обновлены',
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php elseif (Yii::$app->session->hasFlash('profile-admin-error')) : ?>
    <?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Профиль пользователя',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => 'Извините, при обработке запроса произошел сбой. Попробуйте обновить страницу и повторите действие еще раз',
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>