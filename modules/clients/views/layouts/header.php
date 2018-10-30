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


<header class="fixed-top m-0 p-0 menu-bg" id='header'>
    <div class="collapse" id="navbarHeader" style="">
        <a href="#" class="close-menu float-left ml-5 mt-5" 
           role="button" data-toggle="collapse" data-target="#navbarHeader" 
           aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            
            <span class="close-menu-fa" aria-hidden="true">&times;</span><span>Закрыть</span>
        </a>
        <div class="mt-5 row justify-content-between mr-auto col-12">          
            <?= Html::img('/images/navbar/group_46.svg', ['class' => 'mx-auto'])  ?>
        </div>
        <div class="container w-100 col-12 circle-scroll-container px-0">
            <div class="sticky-top col-12">
                <div id="scroll-container" class="">
                    <div class="wrap-container col-12" id="wrap-scroll">
                        <section class="vertical-center-4 slider">
                            <div style="background-color: silver;">
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="<?= Url::to(['clients/index']) ?>">Главная</a></p>
                                </span>
                            </div>
                            <div style="background-color: silver;"> 
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="<?= Url::to(['voting/index']) ?>">Голосование</a></p>
                                </span>
                            </div>
                            <div style="background-color: silver;">
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="<?= Url::to(['profile/index']) ?>">Профиль</a></p>
                                </span>
                            </div>
                            <div style="background-color: silver;">
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="#">Лицевой счет</a></p>
                                </span>
                            </div>
                            <div style="background-color: silver;">
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="<?= Url::to(['requests/index']) ?>">Заявки</a></p>
                                </span>
                            </div>
                            <div style="background-color: silver;">
                                <span class="item">
                                    <p style="width:350px; height: 100px;"><a href="<?= Url::to(['paid-services/index']) ?>">Платные услуги</a></p>
                                </span>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <nav class="navbar navbar-dark menu-collapsed-nav justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <div class="container-fluid menu-collapsed-container carousel-caption">
            <a href="#" class="navbar-brand d-flex align-items-center close-menu">
                <div class="navbar-collapse collapse dual-nav w-50 order-1 order-md-0">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link pl-0" href="#">
                                <button class="btn navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="">
                                        <?= Html::img('/images/navbar/group_23.svg')  ?>
                                    </span>                                    
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            <a href="/" class="navbar-brand mx-auto d-block text-center order-0 order-md-1 w-25">
                <?= Html::img('/images/navbar/group_46.svg')  ?>
            </a>
            <div class="navbar-collapse collapse dual-nav w-50 order-2">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="">
                            <?= Html::img('/images/navbar/user.svg')  ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="popover" data-placement="bottom" data-content="<div class='bell-popover' id='bell-popover'><p class='popover-txt' style=' '>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p></div>">
                            <?= Html::img('/images/navbar/bell.svg')  ?>
                            <span class="badge badge-danger bell-badge p-0 hidden"> </span>
                        </a>
                    </li>
                </ul>
            </div>
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