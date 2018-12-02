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
        <ul class="menu-items menu-scroll" id="menu">
            <li id="item-menu-0">
                <a href="<?= Url::to(['personal-account/index']) ?>" class="first-item">Лицевой счет</a>
            </li>
            <li id="item-menu-1">
                <a href="<?= Url::to(['requests/index']) ?>" class="menu-1">Заявки</a>
            </li> 
            <li id="item-menu-2" class="active-item">
                <a href="<?= Url::to(['clients/index']) ?>" class="menu-2">Новости</a>
            </li>
            <li id="item-menu-3">
                <a href="<?= Url::to(['paid-services/index']) ?>" class="menu-3">Усуги</a>
            </li>
            <li id="item-menu-4">
                <a href="<?= Url::to(['voting/index']) ?>" class="last-item">Опрос</a>
            </li>
        </ul>        
    </div>
    <?= SubBarGeneralPage::widget() ?>
    <?= StatusRequest::widget() ?>
    <?= SubBarPaidService::widget() ?>
    <?= SubBarPersonalAccount::widget() ?>
</nav>

<?php

$menuArrya = [
    '0' => [
        'Item0' => '#0',
    ],
    '1' => [
        'Item1' => '#1',
    ],
    '2' => [
        'Item2' => '#2',
    ],
    '3' => [
        'Item3' => '#3',
    ],
    '4' => [
        'Item4' => '#4',
    ],
];



?>