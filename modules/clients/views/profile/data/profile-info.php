<?php

    use yii\helpers\Html;

/* 
 * Данные пользователя
 */
?>

<?= Html::img(Yii::$app->userProfile->photo, ['class' => 'img-circle', 'alt' => 'user photo']) ?>
<p class="profile-page__user__name">
    <?= Yii::$app->userProfile->surname ?>
    <br />
    <?= Yii::$app->userProfile->name . ' ' . Yii::$app->userProfile->secondName ?>
</p>
<p class="profile-page__user-info">
    <i class="glyphicon glyphicon-phone"></i>&nbsp;
    <?= Yii::$app->userProfile->mobile ?>
</p>
<p class="profile-page__user-info">
    <i class="glyphicon glyphicon-envelope"></i>&nbsp;
    <?= Yii::$app->userProfile->email ?>
</p>
