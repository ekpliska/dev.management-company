<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\modules\clients\widgets\NavMenu;
    use app\modules\clients\widgets\UserInfo;
    use app\modules\clients\widgets\Notifications;
    use app\modules\clients\widgets\SubBarGeneralPage;
    use app\modules\clients\widgets\SubBarPaidService;
    use app\modules\clients\widgets\SubBarPersonalAccount;
    use app\modules\clients\widgets\StatusRequest;
/*
 * Шапка, меню, хлебные крошки
 */
?>

<nav class="navbar navbar-fixed-top navbar-menu">
    <div class="container-fluid">
        <div class="navbar-header col-md-2">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#elseNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="<?= Url::to(['clients/index']) ?>">
                <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
            </a>
        </div>
        
        <div class="collapse navbar-collapse" id="elseNavbar">
            <ul class="nav navbar-nav">
                <li>
                    <p class="list-account__title">Лицевой счет</p>
                    <?= Html::dropDownList('_list-account', $this->context->_current_account_id, $this->context->_lists, [
                            'placeholder' => $this->context->_current_account_number,
                            'class' => 'select-current-account',
                            'data-client' => Yii::$app->user->can('clients') ? $user_info->clientID : '',
                    ]) ?>
                </li>
                <li>
                    <p class="account-balance">
                        <span class="<?= Yii::$app->userProfile->balance > 0 ? 'defaul' : 'minus' ?>">
                            <i class="glyphicon glyphicon-ruble"></i>
                        </span>
                        <span>
                            <?= Yii::$app->userProfile->balance ?>
                        </span>
                    </p>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right user-profile">
                    <?= UserInfo::widget() ?>
                <?= Notifications::widget() ?>
            </ul>
        </div>
    </div>
</nav>

<?php /* 
<nav class="navbar navbar-fixed-top navbar-menu">
    <div class="container-fluid navbar-menu_header">
        <div class="navbar-header">
            <a class="menu-toggle" href="#menu">
                <span></span><p class="menu-toggle_message">Меню</p>
            </a>        
        </div>
        <a href="<?= Url::to(['clients/index']) ?>" class="brand">
            <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
        </a>
        <ul class="nav navbar-nav navbar-right user-notification">
            <?= UserInfo::widget(['_value_choosing' => $this->context->_current_account_number]) ?>
            <?= Notifications::widget() ?>            
        </ul>
    </div>  
    <div class="container-fluid navbar-menu__items text-center">
        <?= NavMenu::widget(); ?>   
    </div>
    <div class="container-fluid navbar-mobile-menu__items text-center">
        <?= NavMenu::widget(['view_name' => 'mobile-menu']); ?>
    </div>
    <?php /* = SubBarGeneralPage::widget() ?>
    <?= StatusRequest::widget(['account_id' => $this->context->_current_account_id]) ?>
    <?= SubBarPaidService::widget() ?>
    <?= SubBarPersonalAccount::widget() 
</nav>*/ ?>