<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>


<ul class="nav navbar-nav navbar-right">
    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?= FormatHelpers::formatFullUserName($user_info['surname'], $user_info['name'], $user_info['second_name']) ?>
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <div class="navbar-content">
                    <div class="row">
                        <div class="col-md-5">
                            <?php if ($user_info['photo']) : ?>
                                <?= Html::img($user_info['photo'], ['alt' => $user_info['surname'], 'class' => 'img-responsive']) ?>
                            <?php else : ?>
                                <?= Html::img('/images/no-avatar.jpg', ['class' => 'img-responsive', 'alt' => 'no-avatar']) ?>
                            <?php endif; ?>
                            <p class="text-center small">
                                ...
                            </p>
                        </div>
                        <div class="col-md-7">
                            <span>
                                <?= FormatHelpers::formatFullUserName($user_info['surname'], $user_info['name'], $user_info['second_name']) ?>
                            </span>
                            <p class="text-muted small">
                                <?= $user_info['email'] ?>
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
