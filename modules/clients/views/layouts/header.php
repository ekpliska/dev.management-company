<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\modules\clients\widgets\UserInfo;
    use app\modules\clients\widgets\Rubrics;
    use app\modules\clients\widgets\SubBarPaidService;
/*
 * Шапка, меню, хлебные крошки
 */
?>

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
                            <p style="width:100%; height: 42.5px;">
                                <a href="<?= Url::to(['clients/index']) ?>">Главная</a>
                            </p>
                        </span>
                    </div>
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height: 42.5px;">
                                <a href="<?= Url::to(['voting/index']) ?>">Голосование</a>
                            </p>
                        </span>
                    </div>
                    <div class="border-menu">
                        <span class="item">
                            <p style="width:100%; height: 42.5px;">
                                <a href="<?= Url::to(['profile/index']) ?>">Профиль</a>
                            </p>
                        </span>
                    </div>
                    <div class="border-menu">      
                        <span class="item"> 
                          <nav class="nav menu-big">
                          <a class="nav-link nav-bt" href="#">Лицевой счет</a>
                          <a class="nav-link nav-bt nav-podmenu" href="#">Квитанция ЖКУ</a>
                          <a class="nav-link nav-bt nav-podmenu" href="#">Показания приборов учета</a>
                          <a class="nav-link nav-bt nav-podmenu" href="#">Платежи</a>
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
                            <p style="width:100%; height:42.5px;">
                                <a href="<?= Url::to(['paid-services/index']) ?>">Платные услуги</a>
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
            <a href="/" class="navbar-brand mx-auto d-block text-center order-0 order-md-1 w-25">
                <?= Html::img('/images/navbar/group_46.svg') ?>
            </a>
            <div class="navbar-collapse collapse dual-nav w-50 order-2">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="">
                            <?= Html::img('/images/navbar/user.svg') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="popover" data-placement="bottom" data-content="" data-original-title="" title="">
                            <label for="checkbox-tenant" data-toggle="modal" data-target="#exampleModale">
                                <?= Html::img('/images/navbar/bell.svg') ?>
                                <span class="badge badge-danger bell-badge p-0 hidden">
                                </span>
                            </label>
                        </a>
                    </li>
                </ul>
            </div>
<!--            <div class="modal fade" id="#modalkeyk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </nav>

    <?= Rubrics::widget() ?>
    <?= SubBarPaidService::widget() ?>
    
</header>

<?php
$this->registerJs('
    $(".current__account_list").on("change", function() {
        var idAccount = $(this).val();
        
        $.ajax({
            url: "' . yii\helpers\Url::to(['app-clients/current-account']) . '",
            method: "POST",
            typeData: "json",
            data: {
                idAccount: idAccount,
            },
            error: function() {
                console.log("#1001 - Ошибка Ajax запроса");
            },
            success: function(response) {
                // console.log(response.success);
            }
        });

    })
')
?>