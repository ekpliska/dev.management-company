<?php

    use yii\helpers\Url;

/* 
 * Дополнительное навигационное меню в разделе "Лицевой счет"
 */
?>

<?php if (Yii::$app->controller->id == 'personal-account') : ?>
<?php $action = Yii::$app->controller->action->id; ?>
    <div class="container-fluid navbar_personal-account">
        <ul class="nav nav-pills sub-menu_account">
            <li class="<?= $action == 'index' ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/index']) ?>"> Общая информация</a>
            </li>
            <li class="<?= $action == 'receipts-of-hapu' ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/receipts-of-hapu']) ?>"> Квитанция ЖКУ</a>
            </li>            
            <li class="<?= $action == 'payments' || 'payment' ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/payments']) ?>"> Платежи</a>
            </li>
            <li class="<?= $action == 'counters' ? 'active' : '' ?>">
                <a href="<?= Url::to(['personal-account/counters']) ?>"> Показания приборов учета</a>
            </li>
        </ul>        
    </div>
<?php endif; ?>