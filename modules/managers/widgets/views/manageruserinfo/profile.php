<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Быстрый доступ к профилю пользователя 
 */

?>

<li class="dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/user.svg') ?>
    </a>
    <ul class="dropdown-menu in_navbar">
        <?php if(!Yii::$app->user->identity->user_login == 'administrator') : ?>
        <li class="user-info-box">
            <div class="row">
                <div class="col-lg-5 col-sm-5 col-md-5 text-center">
                    <a href="<?= Url::to(['employee-form/employee-profile', 'type' => 'administrator', 'employee_id' => Yii::$app->userProfileCompany->employeeID]) ?>">
                        <?= Html::img(Yii::$app->userProfileCompany->photo, ['class' => 'img-rounded photo-user-dropdown']) ?>                        
                    </a>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 dropdown_user-info">
                    <p class="dropdown_user-name">
                        <?= Yii::$app->userProfileCompany->fullNameEmployee ?>                        
                    </p>
                    <div class="dropdown-menu_link-profile">
                        <?= Html::a('Мой профиль', ['employee-form/employee-profile', 'type' => 'administrator', 'employee_id' => Yii::$app->userProfileCompany->employeeID]) ?>
                    </div>                    
                    <div class="mail-border">
                        <p class="mail-color">
                            <?= Yii::$app->userProfileCompany->email ?>
                        </p>
                    </div>
                    <div class="dropdown_account-block">
                        <p class="dropdown_account-title">Контактный телефон</p>
                        <p class="dropdown_account-number"><?= Yii::$app->userProfileCompany->mobile ?> </p>  
                    </div>
                </div>
            </div>
        </li>
        <?php endif; ?>
        <li class="text-light dropdown_footer">
            <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                        'data' => [
                            'method' => 'post'], 
                        'class' => 'float-right footer_link-logout']) ?>                
            </div>
        </li>
    </ul>
</li>