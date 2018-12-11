<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\clients\widgets\Notifications;
    use app\modules\managers\widgets\NavManager;
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
            <?php /*= UserInfo::widget(['_value_choosing' => $this->context->_value_choosing]) */ ?>
            <?= Notifications::widget() ?>
        </ul>
    </div>
    <?= NavManager::widget() ?>
</nav>