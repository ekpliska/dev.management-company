<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\widgets\AddRentForm;
    use app\modules\clients\widgets\SubMenuProfile;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль абонента';

// Если пользователь Собственник, то меняем разметку блоков профиля
$col = Yii::$app->user->can('AddNewRent') ? 3 : 2;
?>

<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= SubMenuProfile::widget() ?>

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

    <?php
        $form = ActiveForm::begin([
            'id' => 'profile-form',
            'action' => ['profile/update-profile', 'form' => 'profile-form'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
         ])
    ?>  

    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <br /><br />
    </div>
    
    <!-- Block of customer -->
    <div class="col-md-<?= 12/$col ?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>                    
                </div>
                <div class="text-right">
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?> Арендатор
                </div>
            </div>            
            <div class="panel-body">                
                <div class="container-fluid">
                    <div class="row">
                        
                        <div class="col-md-4" style="padding: 0;">
                            <p>Фамилия</p>
                            <?= Html::input('text', 'surname', $user->client->clients_surname, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        <div class="col-md-4" style="padding: 0;">
                            <p>Имя</p>
                            <?= Html::input('text', 'name', $user->client->clients_name, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        <div class="col-md-4" style="padding: 0;">
                            <p>Отчество</p>
                            <?= Html::input('text', 'second_name', $user->client->clients_second_name, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        
                        <p>Домашний телефон</p>
                        <div class="col-md-12" style="padding: 0;">
                            <?= Html::input('text', 'phone', $user->client->clients_phone, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        
                        <p>Мобильный телефон</p>
                        <div class="col-md-12" style="padding: 0;">
                            <?= Html::input('text', 'mobile', $user->client->clients_mobile, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        
                        <p>Электронная почта</p>
                        <div class="col-md-12" style="padding: 0;">
                            <?= Html::input('text', 'email', $user->user_email, ['class' => 'form-control', 'disabled' => true]) ?>
                        </div>
                        
                        <p>Номер лицевого счета</p>
                        <div class="col-md-12" style="padding: 0;">
                            <?= Html::input('text', 'account-number', 
                                    $user->client->personalAccount->account_number, ['class' => 'form-control', 'disabled' => true, 'id' => 'account-number']) ?>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <!-- End block of customer -->
    
    <!-- Block of avatar and notifications -->
    <div class="col-md-<?= 12/$col ?>">        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Фотография</strong></div>
            <div class="panel-body">
                <div class="text-center">
                    
                    <?php if (empty($user->user_photo)) : ?>
                        <?= Html::img('/images/no-avatar.jpg', ['class' => 'img-circle', 'alt' => 'no-avatar', 'width' => 150]) ?>
                    <?php else: ?>
                        <?= Html::img($user->user_photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user->user_login, 'width' => 150]) ?>
                    <?php endif; ?>

                </div>
                    
                <?= $form->field($user, 'user_photo')->input('file', ['id' => 'btnLoad']) ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Оповещения</strong></div>
            <div class="panel-body">
                <?= $form->field($user, 'user_check_sms')->checkbox() ?>
                <?= $form->field($user, 'user_check_email')->checkbox() ?>
            </div>
        </div>
        
    </div>
    <!-- End block of avatar and notifications -->
   
    <?php ActiveForm::end(); ?>    
    
    <?php if (Yii::$app->user->can('AddNewRent')) : ?>
    
    <div class="col-md-<?= 12/$col ?>">
        <?php if (count($accounts_list) > 1): ?>
            <?= Html::dropDownList('_list-account', $this->context->_choosing, $accounts_list, ['class' => 'form-control', 'id' => '_list-account']) ?>
            <br />
        <?php endif; ?>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Контактные данные арендатора</strong>                
            </div>
            <div class="panel-body info_rent">
                <div id="content-replace">

                    <?= $this->render('_form/rent-view', [
                            'model' => $accounts_info, 
                            'model_rent' => $model_rent, 
                            'add_rent' => $add_rent, 
                        ]) ?>
                    
                </div>
            </div>
        </div>
        
        <?php if ($not_active_rents) : ?>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Неактивные арендаторы</strong></div>
                <div class="panel-body info_rent">
                    <div id="content-replace">
                        У вас имеются неактивные арендаторы. Арендатор считается неактивным, если не зареплен ни за одним лицевым счетом.
                        <br />
                        <span class="glyphicon glyphicon-remove-circle"></span> - Удалить
                        <span class="glyphicon glyphicon-ok-circle"></span> - Связать с лицевым счетом
                        <br /><br />
                        <?php foreach ($not_active_rents as $rent) : ?>
                        <div class="row">
                            <div class="col-8 col-sm-2">
                                <?php if ($rent->user->user_photo) : ?>
                                    <?= Html::img($rent->user->user_photo, ['style' => 'width: 50px;']) ?>
                                <?php else: ?>
                                    <?= Html::img('@web/images/no-avatar.jpg', ['style' => 'width: 50px;']) ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-8 col-sm-10" style="padding-left: 15px; border-radius: 5px; position: relative; top: 5px;">
                                
                                <?= $rent->fullName ?>
                                <br />
                                <?= $rent->rents_mobile ?>
                                <br />
                                
                                <?= Html::button('<span class="glyphicon glyphicon-remove-circle"></span>', [
                                    'data-record-id' => $rent->rents_id,
                                    'data-record-fullname' => $rent->fullName,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#confirm-delete',
                                ]) ?>

                                <?= Html::button('<span class="glyphicon glyphicon-ok-circle"></span>', [
                                    'data-rent' => $rent->rents_id,
                                    'data-rent-fullname' => $rent->fullName,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#bind-rent-modal',
                                ]) ?>
                          
                                <hr />
                            </div>
                        </div>                       
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
            
    </div>
    
    <?php endif; ?>


</div>


<?php /* Модальное окно, активизируется, когда нажат checkbox Арендатор */ ?>
<div class="modal fade" id="changes_rent" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><button class="close changes_rent__close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Дальнейшее действия с учетной записью арендатора
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Какое действие вы хотите совершить с учетной записью арендатора? 
                    <br />
                    <span id="rent"></span>
                    <span id="rent-surname"></span>
                    <span id="rent-name"></span>
                    <span id="rent-second-name"></span>
                    <br />
                    *** Внимание, при удалении арендатора так же будет удалена его учетная запись на портале
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger changes_rent__del" data-dismiss="modal">Удалить</button>
                <button class="btn btn-success changes_rent__undo" data-dismiss="modal">Отвязать от лицевого счета</button>
                <button class="btn btn-primary changes_rent__close" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<?php /* Модальное окно на подтверждение удаления аредатора */ ?>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Подтверждение удаления</h4>
            </div>
            <div class="modal-body">
                <p>Вы собираетесь удалить арендатора <b><i class="title"></i></b> и его учетную запись на портале.</p>
                <p>Продолжить?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-ok-delete">Удалить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>


<?php /* Модальное окно объединить арендатора с лицевым счетом */ ?>
<div class="modal fade" id="bind-rent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Объеденить арендатора с лицевым счетом</h4>
            </div>
            <div class="modal-body">
                <p>Для продолжения процедуры объединения арендатора с лицевым счетом выберите из списка необходимый лицевой счет.</p>
                <div class="row">
                    <div class="col-md-5">
                        <span class="fullname"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="glyphicon glyphicon-random"></span>
                    </div>
                    <div class="col-md-5">
                        <?= Html::dropDownList('_list-account-rent', null, $accounts_list_rent, ['class' => 'form-control', 'id' => '_list-account-rent']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-ok-bind">Объединить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<?= AddRentForm::widget(['add_rent' => $add_rent]) ?>

<?php

$this->registerJs('
    $("#_list-account").on("change", function() {
    
        var accountNumber = $("input[name*=account-number]");
        var client = ' . $user->client->clients_id . ';
        var account = $(this).val();
        
        accountNumber.val($("#_list-account :selected").text());
        
        $.ajax({
            url: "' . Url::to(['profile/check-account']) . '",
            data: {
                dataClient: client,
                dataAccount: account,
            },
            error: function() {
                console.log("error ajax");            
            },
            dataType: "json",
            type: "POST",
            success: function(response) {
                if (response.is_rent) {
                    $("#is_rent").prop("checked", true);
                } else {
                    $("#is_rent").prop("checked", false);
                }                
               // console.log(response.model);
               $("#content-replace").html(response.data);
            }
        });

    });
')
?>



