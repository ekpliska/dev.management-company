<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\helpers\Html;
    use app\modules\clients\widgets\UserInfo;
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
            ['label' => 'Главная', 'url' => ['clients/index']],
            Yii::$app->user->can('clients') ?
                (['label' => 'Голосование', 'url' => ['clients/vote']]) : '',
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
            ) : '',
            '<li>'
                . UserInfo::widget([
                    '_user' => $this->context->permisionUser(),
                    '_choosing' => $this->context->_choosing, 
                    '_list' => $this->context->getListAccount(Yii::$app->user->identity->id)]) .
            '</li>'
        ],
    ]);
    NavBar::end();
?>

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