<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\modules\clients\widgets\AddRentForm;
    use app\modules\clients\widgets\SubMenuProfile;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\ModalWindows;
    
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
    
    <?= AlertsShow::widget() ?>

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
                        <?= $this->render('data/contact-info', ['user' => $user]); ?>                        
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

        <?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>
        <?= ModalWindows::widget(['modal_view' => 'confirm-delete']) ?>
        <?= ModalWindows::widget(['modal_view' => 'bind-rent-modal', 'list_account' => $accounts_list_rent]) ?>
    
        <?= AddRentForm::widget(['add_rent' => $add_rent]) ?>
    
    <?php endif; ?>

</div>    

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



