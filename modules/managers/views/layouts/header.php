<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\clients\widgets\Notifications;
    use app\modules\managers\widgets\NavManager;
    use app\modules\managers\widgets\SubMenu;
    use app\modules\managers\widgets\ManagerUserInfo;
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
        <a href="<?= Url::to(['managers/index']) ?>" class="brand">
            <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
        </a>
        <ul class="nav navbar-nav navbar-right user-notification">
            <?= ManagerUserInfo::widget() ?>
            <?= Notifications::widget() ?>
        </ul>
    </div>
    <?= SubMenu::widget(['view_name' => 'news']) ?>
    <?= SubMenu::widget(['view_name' => 'designer-request']) ?>
    <?= NavManager::widget() ?>
</nav>