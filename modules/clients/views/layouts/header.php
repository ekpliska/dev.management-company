<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\modules\clients\widgets\UserInfo;
    use app\modules\clients\widgets\Notifications;
    use app\modules\clients\widgets\NavMenu;
/*
 * Шапка, меню, хлебные крошки
 */
?>

<nav class="navbar navbar-fixed-top navbar-menu">
    <div class="container-fluid">
        <div class="navbar-header col-md-2">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#elsaNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="<?= Url::to(['clients/index']) ?>">
                <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
            </a>
        </div>
        
        <div class="collapse navbar-collapse" id="elsaNavbar">
            <ul class="nav navbar-nav">
                <li class="account-info">
                    <p class="list-account__title">Лицевой счет</p>
                    <?= Html::dropDownList('_list-account', $this->context->_current_account_id, $this->context->_lists, [
                            'placeholder' => $this->context->_current_account_number,
                            'class' => 'select-current-account',
                            'data-client' => Yii::$app->user->can('clients') ? $user_info->clientID : '',
                    ]) ?>
                </li>
                <li>
                    <p class="account-balance">
                        <?= Yii::$app->userProfile->balance ?>
                        <span class="<?= Yii::$app->userProfile->balance > 0 ? 'defaul' : 'minus' ?>">
                            <i class="glyphicon glyphicon-ruble"></i>
                        </span>
                    </p>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right user-profile hidden-xs">
                <?= UserInfo::widget() ?>
                <?= Notifications::widget() ?>
            </ul>
            <?= NavMenu::widget(['view_name' => 'mobile-menu']) ?>
        </div>
    </div>
</nav>