<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\dispatchers\widgets\NavDispatchers;
    use app\modules\dispatchers\widgets\DispatcherUserInfo;
    use app\modules\clients\widgets\Notifications;
    use app\modules\dispatchers\widgets\SubMenuGeneralPage;
    
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
        <a href="<?= Url::to(['dispatchers/index']) ?>" class="brand">
            <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
        </a>
        <ul class="nav navbar-nav navbar-right user-notification">
            <?= DispatcherUserInfo::widget() ?>
            <?= Notifications::widget() ?>
        </ul>
    </div>  
    <div class="container-fluid navbar-menu__items text-center">
        <?= NavDispatchers::widget(); ?>   
    </div>
    <div class="container-fluid navbar-mobile-menu__items text-center">
        <?= NavDispatchers::widget(['view_name' => 'mobile-menu']); ?>
    </div>
    <?= SubMenuGeneralPage::widget() ?>
</nav>