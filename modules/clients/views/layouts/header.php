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
        <ul class="menu-items">
            <li>
                <a href="<?= Url::to(['personal-account/index']) ?>">Лицевой счет</a>
            </li>
            <li>
                <a href="<?= Url::to(['requests/index']) ?>">Завяки</a>
            </li> 
            <li>
                <a href="<?= Url::to(['clients/index']) ?>">Новости</a>
            </li>
            <li>
                <a href="<?= Url::to(['paid-services/index']) ?>">Услуги</a>
            </li>
            <li>
                <a href="<?= Url::to(['voting/index']) ?>">Опрос</a>
            </li>
        </ul>        
    </div>
    <?= SubBarGeneralPage::widget() ?>
    <?= StatusRequest::widget() ?>
    <?= SubBarPaidService::widget() ?>
    <?= SubBarPersonalAccount::widget() ?>
</nav>


<?php /*
    <nav class="navbar">
        <div class="nav-menu-sd">
            <a href="#">
                <div class="logo-sd">
                    <?= Html::img('/images/navbar/group_46.svg', ['alt' => 'image'])  ?>
                </div>
            </a>

            <div class="user-sd">

                <ul class="nav navbar-nav navbar-right">
                    <?= UserInfo::widget(['_value_choosing' => $this->context->_value_choosing]) ?>
                    <?= Notifications::widget() ?>
                </ul>
            </div>    
            <a class="menu-btn-sd" href="#menu">
                <span></span><p class="treck-menu">Меню</p>
            </a>
            <div class="menu-sd">
                <ul class="menu-lets">
                    <li><a class="menu-chet" href="<?= Url::to(['personal-account/index']) ?>">Лицевой счет</a>
                        <ul class="podmenu-free">
                            <li><a class="podmenu-voge" href="#">Платежи</a></li>
                            <li><a class="podmenu-voge" href="#">Показания прибора учета</a></li>
                            <li><a class="podmenu-voge" href="#">Квитанция ЖКХ</a></li>
                        </ul>
                    </li>
                    <li><a class="menu-chet" href="<?= Url::to(['requests/index']) ?>">Заявки</a></li>
                    <li><a class="menu-chet active" href="<?= Url::to(['clients/index']) ?>">Новости</a></li>
                    <li><a class="menu-chet" href="<?= Url::to(['paid-services/index']) ?>">Услуги</a></li>
                    <li><a class="menu-chet" href="<?= Url::to(['voting/index']) ?>">Опрос</a></li>
                </ul>
            </div>
            
        </div>
    </nav>


<?php /*
<header class="fixed-top m-0 p-0 menu-bg" id="header">
    <div class="collapse" id="navbarHeader" style="">
        <a href="#" class="close-menu float-left ml-5 mt-5" 
           role="button" data-toggle="collapse" 
           data-target="#navbarHeader" aria-controls="navbarHeader" 
           aria-expanded="false" aria-label="Toggle navigation">
            
            <span class="close-menu-fa" aria-hidden="true">×</span><span>Закрыть</span>
        </a>
        <div class="mt-5 row justify-content-between mr-auto col-12">
            <?= Html::img('/images/navbar/group_46.svg', ['class' => 'mx-auto'])  ?>
        </div>
        <div class=" circle-scroll-container px-0 menu-start" style="text-align: center;">
            <div class="sticky-top col-12">
                <div class="modal_menu">
                    <div class="border-menu">      
                        <span class="item"> 
                          <nav class="nav menu-big">
                          <a class="nav-link nav-bt" href="<?= Url::to(['personal-account/index']) ?>">Лицевой счет</a>
                          <a class="nav-link nav-bt nav-podmenu" href="#">Платежи</a>                          
                          <a class="nav-link nav-bt nav-podmenu" href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>">Квитанция ЖКУ</a>
                          <a class="nav-link nav-bt nav-podmenu" href="<?= Url::to(['personal-account/counters']) ?>">Показания приборов учета</a>
                        </nav>
                        </span>
                    </div>
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height:42.5px;">
                                <a href="<?= Url::to(['requests/index']) ?>">Заявки</a>
                            </p>
                        </span>
                    </div>                    
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height: 42.5px;">
                                <a href="<?= Url::to(['clients/index']) ?>">Новости</a>
                            </p>
                        </span>
                    </div>
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height:42.5px;">
                                <a href="<?= Url::to(['paid-services/index']) ?>">Услуги</a>
                            </p>
                        </span>
                    </div>                                        
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height: 42.5px;">
                                <a href="<?= Url::to(['voting/index']) ?>">Опрос</a>
                            </p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-dark menu-collapsed-nav justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <div class="container-fluid menu-collapsed-container carousel-caption">
            <a href="#" class="navbar-brand d-flex align-items-center close-menu"></a>
            <div class="navbar-collapse collapse dual-nav w-50 order-1 order-md-0">
                <a href="#" class="navbar-brand d-flex align-items-center close-menu"></a>
                <ul class="navbar-nav">
                    <a href="#" class="navbar-brand d-flex align-items-center close-menu"></a>
                    <li class="nav-item active">
                        <a href="#" class="navbar-brand d-flex align-items-center close-menu"></a>
                        <a class="nav-link pl-0" href="#">
                            <button class="btn navbar-toggler" type="button" data-toggle="collapse" 
                                    data-target="#navbarHeader" aria-controls="navbarHeader" 
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <span class="">
                                    <?= Html::img('/images/navbar/group_23.svg') ?>
                                </span>
                            </button>
                        </a>
                    </li>
                </ul>
            </div>
            <a href="<?= Url::to(['clients/index']) ?>" class="navbar-brand mx-auto d-block text-center order-0 order-md-1 w-25">
                <?= Html::img('/images/navbar/group_46.svg') ?>
            </a>
            <div class="navbar-collapse collapse dual-nav w-50 order-2">
                <ul class="nav navbar-nav ml-auto">
                    <?= UserInfo::widget(['_value_choosing' => $this->context->_value_choosing]) ?>
                    <?= Notifications::widget() ?>
                </ul>
            </div>
        </div>
    </nav>

    
</header>
 * 
 */ ?>