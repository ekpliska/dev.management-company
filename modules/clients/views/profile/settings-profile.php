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
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= SubMenuProfile::widget() ?>
    <?= AlertsShow::widget() ?>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Общая информация о профиле</strong>
            </div>
            <div class="panel-body">
                
                <div class="text-center">
                    <?php if (empty($user_info->user_photo)) : ?>
                        <?= Html::img('/images/no-avatar.jpg', ['class' => 'img-circle', 'alt' => 'no-avatar', 'width' => 150]) ?>
                    <?php else: ?>
                        <?= Html::img($user_info->user_photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->user_login, 'width' => 150]) ?>
                    <?php endif; ?>
                </div>

                <hr />
                
                <div class="col-md-12">
                    <p>Фамилия имя отчество: <?= $user_info->client->fullName ?></p>
                    <p>Роль: <?= Yii::$app->authManager->getRolesByUser($user_info->id)["clients"]->description ?></p>                    
                    <p>Логин: <?= $user_info->user_login ?></p>
                    <p>Дата регистрации: <?= FormatHelpers::formatDate($user_info->created_at) ?></p>
                    <p>Дата последнего входа на портал: <?= FormatHelpers::formatDate($user_info->updated_at) ?></p>
                    <p>Статус: <?= $user_info->getUserStatus() ?></p>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Изменить пароль</strong>
            </div>
            <div class="panel-body">
                <?php
                    $form_psw = ActiveForm::begin([
                        'id' => 'change-password-form',
                        'validateOnSubmit' => true,
                        'validateOnBlur' => false,
                        'validateOnChange' => false,
                    ]);
                ?>
                
                    <?= $form_psw->field($model_password, 'current_password')
                            ->input('password', [
                                'placeHolder' => $model_password->getAttributeLabel('current_password'),
                                'class' => 'form-control show_password'
                            ]) 
                    ?>
                
                    <?= $form_psw->field($model_password, 'new_password')
                            ->input('password', [
                                'placeHolder' => $model_password->getAttributeLabel('new_password'),
                                'class' => 'form-control show_password'])
                    ?>
                
                    <?= $form_psw->field($model_password, 'new_password_repeat')
                            ->input('password', [
                                'placeHolder' => $model_password->getAttributeLabel('new_password_repeat'),
                                'class' => 'form-control show_password']) 
                    ?>
                    
                    <div class="form-group">
                        <?= Html::checkbox('show_password', false) ?> <span class="show_password__text">Показать пароли</span>
                    </div>
                    
                    <div class="form-group text-right">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Изменить адрес электронной почты и/или мобильный телефон</strong>
            </div>
            <div class="panel-body">
                <p>
                    Внимание, на указанные номер мобильного телефона и электронную почту будут приходить оповещения с портала. 
                    Отключить оповещения вы можете в разделе <?= Html::a('Профиль', ['profile/index']) ?>.
                </p>
                <?php
                    $form_email = ActiveForm::begin([
                        'id' => 'change-email-form',
                        'validateOnSubmit' => true,
                        'validateOnBlur' => false,
                        'validateOnChange' => false,
                    ]);
                ?>
                
                    <?= $form_email->field($user_info, 'user_email')->input('text')->label() ?>
                
                    <?= $form_email->field($user_info, 'user_mobile')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])
                        ->input('text', [
                            'placeHolder' => $user_info->getAttributeLabel('user_mobile')])
                        ->label() 
                    ?>
                
                    <div class="form-group text-right">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
                
                <?php ActiveForm::end() ?>
            </div>
        </div>       
        
    </div>

</div>