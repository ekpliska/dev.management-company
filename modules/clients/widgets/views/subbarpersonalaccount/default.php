<?php

    use yii\helpers\Url;

/* 
 * Дополнительное навигационное меню в разделе "Лицевой счет"
 */
?>

<?php if (Yii::$app->controller->id == 'personal-account') : ?>
<?php 
    $action = Yii::$app->controller->action->id;
    $url = Yii::$app->urlManager->parseRequest(Yii::$app->request);
?>
    <div class="container-fluid navbar_personal-account">
        <ul class="nav nav-pills sub-menu_account">
            <li class="<?= stristr($url[0], $action) ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/index']) ?>">Общая информация</a>
            </li>
            <li class="<?= stristr($url[0], $action) ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>">Квитанция ЖКУ</a>
            </li>            
            <li class="<?= stristr($url[0], $action) ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/payment']) ?>">Платежи</a>
            </li>
            <li class="<?= stristr($url[0], $action) ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/counters']) ?>">Показания приборов учета</a>
            </li>
        </ul>        
    </div>
<?php endif; ?>