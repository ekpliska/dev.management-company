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
$user = Yii::$app->user->identity->user_id; 
$username = Yii::$app->user->identity->user_login;
$account = Yii::$app->user->identity->user_account_id;   
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
            ['label' => 'Новости', 'url' => ['clients/index']],
            ['label' => 'Голосование', 'url' => ['']],
            ['label' => 'Профиль', 'url' => ['clients/profile', 'user' => $user, 'username' => $username, 'account' => $account]],
            [
                'label' => 'Лицевой счет',
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['personal-account/index', 'user' => $user, 'username' => $username, 'account' => $account]],
                    ['label' => 'Квитанции ЖКУ', 'url' => ['']],
                    ['label' => 'Показания приборов учета', 'url' => ['']],
                    ['label' => 'Платежи', 'url' => ['']],
                ],
            ],
            ['label' => 'Заявки', 'url' => ['requests/index', 'user' => $user, 'username' => $username, 'account' => $account]],
            ['label' => 'Платные услуги', 'url' => ['']],
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
