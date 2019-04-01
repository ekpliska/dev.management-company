<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Notifications;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>

<div class="navbar_user-block">
    <div class="navbar_user-block__image">
        <div class="dropdown">
            <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'user-profile__photo']) ?>
                <span class="<?= (count($notifications_lists) > 0) ? 'notification__dot' : 'hidden' ?>"></span>
            </button>
            <ul class="dropdown-menu notification__dropdown">
                <?php if (!empty($notifications_lists) && count($notifications_lists) > 0) : ?>
                <li class="text-right">
                    <a href="javascript:void(0);" class="notification_reset">Отметить как прочитанные</a>
                </li>
                <li class="user-info-box">
            
                    <?php foreach ($notifications_lists as $note) : ?>
                        <?php if ($note->type_notification == Notifications::TYPE_CHANGE_STATUS_IN_REQUEST) : ?>
                            <?= Html::a("<span class='dot'></span>{$note->message}", ['requests/view-request', 'request_number' => $note->value_1], ['class' => 'notification_link', 'data-notice' => $note->id]) ?>
                        <?php elseif ($note->type_notification == Notifications::TYPE_CHANGE_STATUS_IN_PAID_REQUEST) : ?>
                            <?= Html::a("<span class='dot'></span>{$note->message}", ['paid-services/order-services'], ['class' => 'notification_link', 'data-notice' => $note->id]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </li>
                <?php else: ?>
                    <p>Новых уведомлений нет.</p>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="navbar_user-block__info">
        <p><?= Yii::$app->userProfile->getFullNameClient(false) ?></p>
        <p>
            <?= Html::a('Профиль', ['profile/index']) ?>
            <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                    'data' => ['method' => 'post'], 
                    'class' => 'navbar_user-block_link-logout']) ?>
        </p>
    </div>
</div>



<?php /*
<li class="dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/user.svg') ?>
    </a>
    <ul class="dropdown-menu in_navbar">
        <li class="user-info-box">
            <div class="row">
                <div class="col-lg-5 col-sm-5 col-md-5 text-center">
                    <a href="<?= Url::to(['profile/index']) ?>">
                        <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'img-rounded photo-user-dropdown']) ?>                        
                    </a>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 dropdown_user-info">
                    <p class="dropdown_user-name">
                        <?= Yii::$app->userProfile->getFullNameClient(false) ?>                        
                    </p>
                    <div class="dropdown-menu_link-profile">
                        <?= Html::a('Мой профиль', ['profile/index']) ?>
                    </div>                    
                    <div class="mail-border">
                        <p class="mail-color">
                            <?= Yii::$app->userProfile->email ?> 
                        </p>
                    </div>
                    <div class="dropdown_account-block">
                        <p class="dropdown_account-title">Текущий лицевой счет</p>
                        <p class="dropdown_account-number"><?= $account_number ?></p>                        
                    </div>
                </div>
            </div>
        </li>
        <li class="text-light dropdown_footer">
            <div class="col-lg-6 col-sm-6 col-md-6 text-center">
                <?= Html::a('<i class="fa fa-lock" aria-hidden="true"></i> Изменить пароль', ['profile/settings-profile'], ['class' => 'footer_link']) ?>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 text-center">
                <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                        'data' => [
                            'method' => 'post'], 
                        'class' => 'float-right footer_link-logout']) ?>                
            </div>
        </li>
    </ul>
</li>

 */ ?>