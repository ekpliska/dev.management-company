<?php
    
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Настройки профиля
 */
$this->title = Yii::$app->params['site-name'] . 'Настройки профиля';
?>

<div class="profile-settings row">
    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
        <div class="profile-settings__left">
            <div class="profile-settings__photo">
                <?= Html::img($user_info->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $user_info->username]) ?>
            </div>

            <div class="profile-settings__info">
                #FORM
            </div>            
        </div>
    </div>
    
    <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 profile-settings__right">
        <div class="user-form">

<p class="user-full-name">
    <?= $user_info->fullNameClient ?>
</p>
<p class="profile-settings_block"><span>Пользовательские данные</span></p>
    <span class="user-login-name">Логин: <?= $user_info->username ?></span>
    <span class="user-role-name">Роль: <?= $user_info->role ?></span>
    <span class="user-status">                
        <i class="glyphicon glyphicon-globe status-<?= $user_info->status['value'] ?>"></i> <?= $user_info->status['name'] ?>
    </span>
            
<p class="profile-settings_block"><span>Адрес</span></p>
    <span class="user-span-text">
        <i class="fa fa-map-marker"></i> <?= $user_info->getFullAdress($this->context->_current_account_id) ?>
    </span>
        
<p class="profile-settings_block"><span>Дата регистрации</span></p>
    <span class="user-span-text">        
        <?= FormatHelpers::formatDate($user_info->dateRegister, true, 0, false) ?>
    </span>
        
<p class="profile-settings_block"><span>Дата последнего входа</span></p>
    <span class="user-span-text">
        <?= FormatHelpers::formatDate($user_info->lastLogin, true, 0, false) ?>
    </span>            
            
        </div>
        <div class="settings">
            <p class="profile-settings_title left">
                <i class="glyphicon glyphicon-lock"></i>&nbsp;&nbsp;Изменить пароль учетной записи
            </p>
            <?= $this->render('form/change-password', [
                    'model_password' => $model_password,
                    'sms_model' => $sms_model,
                    'is_change_password' => $is_change_password,
                    'is_change_phone' => $is_change_phone,
            ]) ?>
        </div>
        <div class="settings">
            <p class="profile-settings_title center">
                <i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;Изменить номер мобильного телефона
            </p>
            <?= $this->render('form/change-phone', [
                    'model_phone' => $model_phone,
                    'sms_model' => $sms_model,
                    'user_info' => $user_info,
                    'is_change_phone' => $is_change_phone,
                    'is_change_password' => $is_change_password
            ]) ?>
        </div>
    </div>
</div>

<?php /*
<?= AlertsShow::widget() ?>

<div class="profile-settings row">
    
    <div class="col-md-3 text-center user-image">
        <?= Html::img($user_info->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $user_info->username]) ?>
    </div>
    
    <div class="col-md-9">
        <?= $this->render('data/profile-info', ['user_info' => $user_info]) ?>
    </div>
    
    <div class="col-md-4 settings">
        <p class="profile-settings_title left">
            <i class="glyphicon glyphicon-lock"></i>&nbsp;&nbsp;Изменить пароль учетной записи
        </p>
        <?= $this->render('form/change-password', [
                'model_password' => $model_password,
                'sms_model' => $sms_model,
                'is_change_password' => $is_change_password,
                'is_change_phone' => $is_change_phone,
            ]) ?>        
    </div>
    <div class="col-md-4 settings">
        <p class="profile-settings_title center">
            <i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;Изменить номер мобильного телефона
        </p>
        <?= $this->render('form/change-phone', [
                'model_phone' => $model_phone, 
                'sms_model' => $sms_model,
                'user_info' => $user_info,
                'is_change_phone' => $is_change_phone,
                'is_change_password' => $is_change_password,
            ]) ?>
    </div>
    <div class="col-md-4 settings">
        <p class="profile-settings_title right">
            <i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Изменить адрес электронной почты
        </p>
        <?= $this->render('form/change-email', [
                'user' => $user,
            ]) ?>
    </div>
</div */ ?>