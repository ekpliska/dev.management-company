<?php
    
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/*
 * Профиль пользователя
 */
$this->title = Yii::$app->params['site-name'] . 'Профиль';
?>

<?= AlertsShow::widget() ?>

<div class="profile-page">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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


<?php /*
<div class="profile-page">
    <?php
        $form = ActiveForm::begin([
            'id' => 'profile-form',
            'action' => ['profile/update-profile'],
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ])
    ?>
    
    <div class="profile-bg">
            <div class="profile-name">
                <p class="profile-user-name">
                    <?= $user_info->getFullNameClient(true) ?>
                </p>
            </div>
            <div class="face-container">
            <?= Html::img($user_info->photo, [
                    'class' => 'img-rounded face',
                    'id' => 'photoPreview',
                    'alt' => $user_info->username]) ?>
                <div class="profile-upload">
                    <?= $form->field($user, 'user_photo', [
                                'template' => '<label class="text-center btn-upload" role="button">{input}{label}'])
                            ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])
                            ->label('<span class="glyphicon glyphicon-camera"></span>') ?>
                </div>
            </div>
    </div>
    
    <div class="account-select text-center">
        <div class="account-select_title">
            <span class="badge-personal-account">Лицевой счет</span>
        </div>

        <?= Html::dropDownList('_list-account', $this->context->_current_account_id, $accounts_list, [
                'placeholder' => $this->context->_current_account_number,
                'id' => 'sources',
                'class' => 'custom-select sources',
                'data-client' => Yii::$app->user->can('clients') ? $user_info->clientID : '',
            ]) 
        ?>    
    </div>
    
    <div class="row">
        
        <!--Собственник-->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clients-profile-info">
            <p class="profile-title">
                Мои контактные данные&nbsp;
                <?= Html::a('<i class="glyphicon glyphicon-cog"></i>', ['profile/settings-profile']) ?>
            </p>
            
            <div class="field">
                <label for="user_mobile" class="field-label"><i class="fa fa-mobile"></i> Мобильный телефон</label>
                <?= Html::input('text', 'user_mobile', $user_info->mobile, ['class' => 'field-input', 'readonly' => true]) ?>
            </div>
            
            <div class="field">
                <label for="user_mobile" class="field-label"><i class="fa fa-phone"></i> Домашний телефон</label>
                <?= Html::input('text', 'user_mobile', $user_info->otherPhone, ['class' => 'field-input', 'readonly' => true]) ?>
            </div>
            
            <div class="field">
                <label for="user_email" class="field-label"><i class="fa fa-envelope-o"></i> Электронная почта</label>
                <?= Html::input('text', 'user_email', $user_info->email, ['class' => 'field-input', 'readonly' => true]) ?>
            </div>
            
        </div>
                

        <!--Арендатор-->
        <?php if (Yii::$app->user->can('clients')) : ?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rent-profile-info">
            <p class="profile-title-rent">
                <label class="el-switch">
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?>
                    <span class="el-switch-style"></span>
                    <span class="margin-r">Арендатор</span>
                </label>
                    
            </p>
            
            <div id="content-replace" class="form-add-rent">
            <?php if (isset($is_rent) && $is_rent) : ?>
                <?= $this->render('_form/rent-view', [
                        'form' => $form,
                        'model_rent' => $model_rent]) ?>
            <?php endif; ?>
            </div>      
        </div>
        <?php endif ?>
    </div>
    
    <div class="spam-agree-txt text-center">
        
        <?= $form->field($user, 'user_check_email', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 

        <div class="save-btn-group">
            <div class="text-center">
                <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
            </div>
        </div>

    </div>
    
    <?php ActiveForm::end(); ?>  
    
</div>

<?php if (Yii::$app->user->can('clients')) : ?>

    <?php if (!$is_rent) : ?>
        <?= $this->render('_form/create_rent', ['add_rent' => $add_rent]) ?>
    <?php endif; ?>

    <?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>

<?php endif; */ ?>