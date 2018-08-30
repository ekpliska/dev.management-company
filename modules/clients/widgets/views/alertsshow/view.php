<?php

    use kartik\alert\Alert;

?>

<?php if (Yii::$app->session->hasFlash('success')) : ?>    
    <?=
        Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Профиль обновлен',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => Yii::$app->session->getFlash('success', false),
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?>
    
<?php if (Yii::$app->session->hasFlash('error')) : ?>    
    <?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Профиль обновлен',
            'icon' => 'glyphicon glyphicon-ok-sign',
            'body' => Yii::$app->session->getFlash('error', false),
            'showSeparator' => true,
            'delay' => 0,
        ]);
    ?>
<?php endif; ?> 
