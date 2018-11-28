<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Дополнительное навигационное меню в разделе "Лицевой счет"
 */
?>

<?php if (Yii::$app->controller->id == 'personal-account') : ?>
    <div class="container-fluid navbar_personal-account">
        
<ul class="nav nav-pills sub-menu_account">
    <li class="active">
        <a href="<?= Url::to(['personal-account/index']) ?>">Общая информация</a>
    </li>
    <li>
        <a href="<?= Url::to(['personal-account/payment']) ?>">Платежи</a>
    </li>
    <li>
        <a href="<?= Url::to(['personal-account/counters']) ?>">Показания приборов учета</a>
    </li>
    <li>
        <a href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>">Квитанция ЖКУ</a>
    </li>
</ul>        
     <?php /*   
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/index']) ?>">
                    Общая информация
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/payment']) ?>">
                    Платежи
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/counters']) ?>">
                    Показания приборов учета
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>">
                    Квитанция ЖКУ
                </a>
            </li>            
        </ul> */ ?>
    </div>
<?php endif; ?>