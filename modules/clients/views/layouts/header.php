<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\helpers\Html;
    
/*
 * Шапка, меню, хлебные крошки
 */
    
/* 
 * Параметры формирования ссылок (внутри личного кибинета)
 * @user - ID пользователя
 * @username - Логин пользователя
 * @account - ID Лицевого счета
 */    
?>

<?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['clients/index']],
            ['label' => 'Голосование', 'url' => ['']],
            ['label' => 'Профиль', 'url' => ['profile/index']],
            [
                'label' => 'Лицевой счет',
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['personal-account/index']],
                    ['label' => 'Квитанции ЖКУ', 'url' => ['personal-account/receipts-of-hapu']],
                    ['label' => 'Показания приборов учета', 'url' => ['personal-account/counters']],
                    ['label' => 'Платежи', 'url' => ['']],
                ],
            ],
            ['label' => 'Заявки', 'url' => ['requests/index']],
            [
                'label' => 'Платные услуги', 
                'items' => [
                    ['label' => 'История услуг', 'url' => ['paid-services/index']],
                    ['label' => 'Заказать услугу', 'url' => ['paid-services/order-services']],
                ],
            ],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->user_login . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
?>
