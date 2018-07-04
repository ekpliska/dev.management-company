<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\helpers\Html;
    
/*
 * Шапка, меню, хлебные крошки
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
            ['label' => 'Новости', 'url' => ['clients/index']],
            ['label' => 'Голосование', 'url' => ['']],
            ['label' => 'Профиль', 'url' => ['clients/profile', 'username' => Yii::$app->user->identity->user_login]],
            [
                'label' => 'Лицевой счет',
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['']],
                    ['label' => 'Квитанции ЖКУ', 'url' => ['']],
                    ['label' => 'Показания приборов учета', 'url' => ['']],
                    ['label' => 'Платежи', 'url' => ['']],
                ],
            ],
            ['label' => 'Заявки', 'url' => ['']],
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
