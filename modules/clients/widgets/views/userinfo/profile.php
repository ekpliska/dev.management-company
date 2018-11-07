<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>
<li class="nav-item dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/user.svg') ?>
    </a>
    <ul class="dropdown-menu">
        <li class="user-info-box">
            <div class="row">
                <div class="col-lg-3 col-sm-3 col-3 text-center">
                    <?= Html::img(Yii::$app->userProfile->photo, ['class' => 'w-50 rounded-circle photo-user-dropdown']) ?>
                </div>
                <div class="col-lg-8 col-sm-8 col-8 dropdown_user-info">
                    <strong class="dropdown_user-name">David John</strong>
                    <div class="mail-border">
                        <p><a href="#" class="mail-color">Iam_Mayer@gmail.com</a></p>
                    </div>
                    <p class="dropdown_account-title">Текущий лицевой счет</p>
                    <p class="dropdown_account-number">#########</p>
                    <!--<small class="text-warning">27.11.2015, 15:00</small>-->
                </div>
            </div>
        </li>
        <li class="text-light dropdown_footer">
            <div class="col-lg-12 col-sm-12 col-12">
                <?= Html::a('Изменить пароль', ['profile/settings-profile']) ?>
                <!--<span>Изменить пароль</span>-->
                <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('Выйти', ['class' => 'float-right text-light'])?>
                <?= Html::endForm() ?>
                <!--<a href="" class="float-right text-light">Выйти</a>-->
            </div>
        </li>
    </ul>
</li>

<?php /*

<ul class="nav navbar-nav navbar-right">
    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?= $user_info->fullNameClient ?>
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <div class="navbar-content">
                    <div class="row">
                        <div class="col-md-5">
                                <?= Html::img($user_info->photo, ['alt' => $user_info->username, 'class' => 'img-responsive']) ?>
                            <p class="text-center small">
                                ...
                            </p>
                        </div>
                        <div class="col-md-7">
                            <span>
                                <?= $user_info->fullNameClient ?>
                            </span>
                            <p class="text-muted small">
                                <?= $user_info->email ?>
                            </p>
                            <p class="text-muted small">
                                <?= Html::beginForm(['/'], 'post') ?>
                                <?= Html::dropDownList('current__account_list', $choosing, $list, [
                                        'class' => 'form-control current__account_list',
                                        'style' => 'margin-top: 7px'])
                                ?>
                                <?= Html::endForm() ?>
                            </p>
                            <div class="divider">
                            </div>
                            <?= Html::a('Профиль', ['profile/index'], ['class' => 'btn btn-primary btn-sm active']) ?>
                        </div>
                    </div>
                </div>
                <div class="navbar-footer">
                    <div class="navbar-footer-content">
                        <div class="row">
                            <div class="col-md-6">
                                <?= Html::a('Изменить пароль', ['profile/settings-profile'], ['class' => 'btn btn-default btn-sm']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= Html::beginForm(['/site/logout'], 'post') ?>
                                <?= Html::submitButton('Выйти', [
                                        'class' => 'btn btn-default btn-sm pull-right'])
                                ?>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </li>
</ul>
*/ ?>