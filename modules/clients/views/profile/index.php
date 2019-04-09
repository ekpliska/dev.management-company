<?php
    
    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;
    
/*
 * Профиль пользователя
 */
$this->title = Yii::$app->params['site-name'] . 'Профиль';
?>

<div class="profile-page">
    <div class="row">
        <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
            <h1>
                Личная информация
            </h1>
            <div class="profile-page__user">
                <?= $this->render('data/profile-info') ?>
                <div class="profile-page__btn-block">
                    <?= Html::a('Настройки профиля', ['profile/settings']) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
            <h1>
                Лицевой счет
            </h1>
            <div class="profile-page__account">
                <?= $this->render('data/account-info', [
                        'account_info' => $account_info,
                ]) ?>
                <?php if (Yii::$app->user->can('clients')) : ?>
                    <div class="profile-page__btn-block">
                        <?= Html::button('Добавить лицевой счет', [
                            'data-toggle' => 'modal',
                            'data-target' => '#create-account-modal'
                        ]) ?>
                    </div>
                    <?= $this->render('form/create_account', ['model' => $model]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <?= $this->render('profile-control/profile-control', [
                'account_info' => $account_info,
                'add_rent' => $add_rent,
                'rent_info' => $rent_info,
                'payment_history' => $payment_history,
                'counters_indication' => $counters_indication,
        ]) ?>
    </div>
</div>