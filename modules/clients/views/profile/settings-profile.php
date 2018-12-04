<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Настройки профиля
 */

$this->title = 'Настройки';
?>


<div class="profile-settings row">
    
    <div class="col-md-3 text-center user-image">
        <?= Html::img($user_info->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $user_info->username]) ?>
    </div>
    
    <div class="col-md-9">
        <?= $this->render('data/profile-info', ['user_info' => $user_info]) ?>
    </div>
    
    <div class="col-md-4 settings">
        <p class="profile-settings_title left"><i class="glyphicon glyphicon-lock"></i>&nbsp;&nbsp;Изменить пароль учетной записи</p>
        <?= $this->render('_form/change-password', [
                'model_password' => $model_password,
                'sms_model' => $sms_model,
                'is_change_password' => $is_change_password]) ?>
    </div>
    <div class="col-md-4 settings">
        <p class="profile-settings_title center"><i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;Изменить номер мобильного телефона</p>
        <?php // = $this->render('_form/change-password', ['model_password' => $model_password]) ?>
    </div>
    <div class="col-md-4 settings">
        <p class="profile-settings_title right"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Изменить электронный адрес</p>
        <?php // = $this->render('_form/change-email', ['user' => $user]) ?>
    </div>
</div>