<?php
    
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/*
 * Профиль пользователя
 */
$this->title = Yii::$app->params['site-name'] . 'Профиль';
$this->params['breadcrumbs'][] = 'Профиль';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<?= AlertsShow::widget() ?>

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
    
    <div class="profile-bg text-center">
        <div class="face-container">
            <?= Html::img($user_info->photo, [
                    'class' => 'img-rounded face',
                    'id' => 'photoPreview',
                    'alt' => $user_info->username]) ?>
        </div>
        <div class="profile-name">
            <p class="profile-user-name text-center">
                <?= $user_info->getFullNameClient(true) ?>
            </p>
        </div>
        <div class="profile-upload">
            <?= $form->field($user, 'user_photo', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}'])
                    ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
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
                'data-client' => Yii::$app->user->can('clients') ? $user_info->clientID : '']) 
        ?>    
    </div>
    
    <div class="row">
        
        <!--Собственник-->
        <div class="col-md-6 clients-profile-info">
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
        <div class="col-md-6 rent-profile-info">
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

        <div class="save-btn-group mx-auto">
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

<?php endif; ?>