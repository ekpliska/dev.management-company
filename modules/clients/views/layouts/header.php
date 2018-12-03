<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
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
    <div class="container-fluid navbar-menu_header">
        <div class="navbar-header">
            <a class="menu-toggle" href="#menu">
                <span></span><p class="menu-toggle_message">Меню</p>
            </a>        
        </div>
        <a href="#" class="brand">
            <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
        </a>
        <ul class="nav navbar-nav navbar-right user-notification">
            <?= UserInfo::widget(['_value_choosing' => $this->context->_value_choosing]) ?>
            <?= Notifications::widget() ?>            
        </ul>
    </div>  
    <div class="container-fluid navbar-menu__items text-center">
        <?= \app\modules\clients\widgets\NavMenu::widget(); ?>   
    </div>
    <?= SubBarGeneralPage::widget() ?>
    <?= StatusRequest::widget() ?>
    <?= SubBarPaidService::widget() ?>
    <?= SubBarPersonalAccount::widget() ?>
</nav>