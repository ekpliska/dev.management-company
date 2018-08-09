<?php
    
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\helpers\FormatHelpers;
    
/* 
 * Настройки профиля
 */

$this->title = 'Настройки';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <!-- Nav menu at block of profile -->
    <ul class="pager">
        <li><a href="<?= Url::to(['profile/index']) ?>">Профиль</a></li>
        <li><a class="active" href="<?= Url::to(['profile/settings-profile']) ?>">Настройки</a></li>
        <li><a href="<?= Url::to(['profile/history']) ?>">История</a></li>
    </ul>
    <!-- End nav menu at block of profile -->
    
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-info" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('success', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                    
        </div>
    <?php endif; ?>
    
    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert alert-error" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('error', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                
        </div>
    <?php endif; ?>

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
                
                    <?= $form_psw->field($model_password, 'current_password')->input('password', ['class' => 'form-control show_password']) ?>
                    <?= $form_psw->field($model_password, 'new_password')->input('password', ['class' => 'form-control show_password']) ?>
                    <?= $form_psw->field($model_password, 'new_password_repeat')->input('password', ['class' => 'form-control show_password']) ?>
                    
                    <div class="form-group">
                        <?= Html::checkbox('show_password', false) ?> <span class="show_password__text">Показать пароли</span>
                    </div>
                    
                    <div class="form-group text-right">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
<?php
$this->registerJs('
    $("input[type=checkbox]").on("change", function() {
        var isShow = $(this);
        if (isShow.is(":checked")) {
            $(".show_password").attr("type", "text");
            $(".show_password__text").text("Скрыть отображение паролей");
        } else {
            $(".show_password").attr("type", "password");
            $(".show_password__text").text("Показать пароли");            
        }
    })
')
?>
                
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
                
                    <?= $form_email->field($user_info, 'user_mobile')->input('text')->label() ?>
                
                    <div class="form-group text-right">
                        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                    </div>
                
                <?php ActiveForm::end() ?>
            </div>
        </div>       
        
    </div>

    
    
</div>