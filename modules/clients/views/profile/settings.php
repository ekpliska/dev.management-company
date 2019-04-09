<?php
    
    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Настройки профиля
 */
$this->title = Yii::$app->params['site-name'] . 'Настройки профиля';
?>

<div class="profile-settings">
    <div class="user-form">
        <?= $this->render('data/profile-info-settings', ['user_info' => $user_info]) ?>            
    </div>
    
    <div class="profile-settings__settings row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="user-info-block">
                <?= $this->render('form/user-profile', ['user' => $user]) ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
</div>