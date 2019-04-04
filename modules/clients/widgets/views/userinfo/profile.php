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
                <?= Html::a('Выйти', ['/site/logout'], [
                        'data' => ['method' => 'post'], 
                        'class' => 'navbar_user-block_link-logout']) ?>
            </p>
        </div>
        <div class="navbar_user-block__image">
            <a href="<?= Url::to(['profile/index']) ?>">
                <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'user-profile__photo']) ?>
            </a>
        </div>
    </div>
</li>