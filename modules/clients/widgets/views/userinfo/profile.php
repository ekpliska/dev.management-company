<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Notifications;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>
<li>
    <div class="navbar_user-block">
        <div class="navbar_user-block__info">
            <p><?= Yii::$app->userProfile->surname . ' ' . Yii::$app->userProfile->name ?></p>
            <p>
                <?= Html::a('Профиль', ['profile/index']) ?>
                <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                        'data' => ['method' => 'post'], 
                        'class' => 'navbar_user-block_link-logout']) ?>
            </p>
        </div>
        <div class="navbar_user-block__image">
            <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'user-profile__photo']) ?>
        </div>
    </div>
</li>