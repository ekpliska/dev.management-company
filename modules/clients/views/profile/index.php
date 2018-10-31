<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use yii\bootstrap4\Modal;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль собственника';
?>

<?php //= SubMenuProfile::widget() ?>
<?php // = AlertsShow::widget() ?>


<?php
    $form = ActiveForm::begin([
        'id' => 'profile-form',
        'action' => ['profile/update-profile', 'form' => 'profile-form'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'validateOnChange' => true,
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'border-container d-block material m-0 p-0 border-container',
        ],
    ])
?>

<div class="col-12 p-0 m-0 text-center profile-bg">
    <div class="face-container">
        <?= Html::img($user_info->photo, [
                'class' => 'rounded-circle mx-auto face',
                'id' => 'photoPreview',
                'alt' => $user_info->username,]
            ) ?>
    </div>
    <div class="profile-name mx-auto">
        <h5 class="profile-name-h text center ">
            <?= $user_info->getFullNameClient(true) ?>
        </h5>
    </div>
    <div class="profile-upload">
        <?= $form->field($user, 'user_photo', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}'])
                ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
    </div>   
</div>
    
<div class="col-12 p-0 m-0 text-center material ">
    <div class="account-select mx-auto">
        <div class="chip-label">
            <span class="badge badge-darkblue">Лицевой счет</span>
        </div>
        
        <?= Html::dropDownList('_list-account', $this->context->_choosing, $accounts_list, [
                'id' => '_list-account',
                'data-client' => $user_info->clientID]) 
        ?>
                
    </div>
    
    
    
    <div class="col-12 mx-0 row personal-info">
        
        <!--Собственник-->
        
        <div class="col-6 clients-profile-info">
            <h5 class="profile-title">Мои контактные данные</h5>
                    
            <?= $form->field($user, 'user_mobile')
                    ->widget(MaskedInput::className(), [
                        'mask' => '+7 (999) 999-99-99'])
                    ->input('text', [
                        'class' => 'mx-auto py-3 d-block form-control input-registration',
                        'placeholder' => $user->getAttributeLabel('user_mobile')])
                    ->label(false) ?>
                    
            <?php /* = $form->field($user, 'user_mobile')
                    ->widget(MaskedInput::className(), [
                        'mask' => '+7 (999) 999-99-99'])
                    ->input('text', [
                        'class' => 'mx-auto py-3 d-block form-control input-registration',
                        'placeholder' => $user->getAttributeLabel('user_mobile')])
                    ->label(false) */ ?>
                    
            <?= $form->field($user, 'user_email')
                    ->input('text', [
                        'class' => 'mx-auto py-3 d-block form-control input-registration',
                        'placeholder' => $user->getAttributeLabel('user_email')])
                    ->label(false) ?>
        </div>
                

        <!--Арендатор-->
        
        <div class="col-6 rent-profile-info">
            <h5 class="profile-title-rent">
                <label class="el-switch">
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?>
                    <span class="el-switch-style"></span>
                    <span class="margin-r">Арендатор</span>
                </label>
                    
            </h5>
            
            <div id="content-replace" class="form-add-rent">`
            <?php if (isset($is_rent) && $is_rent) : ?>
                <?= $this->render('_form/rent-view', [
                        'form' => $form,
                        'model_rent' => $model_rent]) ?>
            <?php else : ?>
                <p>Арендатор отсутствует</p>
            <?php endif; ?>
            </div>      
        </div>
                
    </div>
            
    <div class="col-12 spam-agree-txt">
        <div class="el-checkbox">
            <?= $form->field($user, 'user_check_email')->checkbox()->label(); ?>
        </div>

<!--<label class="el-checkbox el-checkbox-red">
    <span class="margin-r">large checkbox</span>
    <input type="checkbox" name="check" checked="">
    <span class="el-checkbox-style  pull-right"></span>
</label>        -->
        
<!--        
        <div class="el-checkbox">
            <span class="margin-r">Unchecked</span>
            <input type="checkbox" name="check" id="1_1">
            <label class="el-checkbox-style" for="1_1"></label>
        </div>-->

        <div class="save-btn-group mx-auto">
            <div class="text-center">
                <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
            </div>
        </div>

    </div>            

<?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs("
    $('form.material').materialForm();
");
?>

<?php if (!$is_rent) : ?>
    <?= $this->render('_form/create_rent', ['add_rent' => $add_rent]) ?>
<?php endif; ?>

<?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>