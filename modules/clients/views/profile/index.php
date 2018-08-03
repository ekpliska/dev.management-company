<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
    use yii\widgets\MaskedInput;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль абонента';
?>

<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
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
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>                    
                </div>
                <div class="text-right">
                    <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?> Арендатор
                    <?php
                        /* Обработка события при клике на checkBox "Арендатор"
                         * Если за лицевым счетом закреплен арендатор, то 
                         * выводим модальное окно для управления учетной записью арендатора
                         */
                        $this->registerJs('
                            $("#is_rent").change(function(e) {
                                var rentsId = $("input[id=_rents]").val();
                                if ($("input").is("#_rents")) {
                                    $("#changes_rent").modal("show");
                                    
                                    $.ajax({
                                        url: "get-rent-info?rent=" + rentsId,
                                        method: "POST",
                                        dataType: "json",
                                        data: {
                                            rent_id: rentsId,
                                        },
                                        success: function(response) {
                                            if (response.status) {
                                                $("#changes_rent #rent-surname").text(response.rent.rents_surname);
                                                $("#changes_rent #rent-name").text(response.rent.rents_name);
                                                $("#changes_rent #rent-second-name").text(response.rent.rents_second_name);
                                            } else {
                                                console.log("Ошибка при получении данных арендатора");
                                            }
                                        }
                                    });
                                }
                            });
                        ');
                    ?>
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
    
    <div class="col-md-4">        
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
        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Настройки учетной записи</strong></div>
            <div class="panel-body">
                <?= Html::a('Изменить пароль', ['/'], ['class' => 'btn btn-default']) ?>
                <?= Html::a('Привязать арендатора', ['/'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>        
        
    </div>
   
    <div class="col-md-4">
        <?php // if (count($accounts_list) > 1): ?>
            <?= Html::dropDownList('_list-account', null, $accounts_list, ['class' => 'form-control', 'id' => '_list-account']) ?>
            <br />
        <?php //  endif; ?>
        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Контактные данные арендатора</strong></div>
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
    </div>
    
    <div class="col-md-12 text-right">
        <?php ActiveForm::end(); ?>
    </div>

</div>


<?php /* Модальное окно на подтверждение удаления аредатора */ ?>
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

<?php
/* Обработка событий в модальном окне "Дальнейшие действия с учетной записью арендатора" */

$this->registerJs('
    
   
    // Закрыть модальное окно
    $(".changes_rent__close").on("click", function() {
        $("#is_rent").prop("checked", true);
    });
    
    // Удалить данные арендатора из системы
    $(".changes_rent__del").on("click", function() {
    
        var rentsId = $("input[id=_rents]").val();
        var accountId = $("#_list-account :selected").text();
        alert(accountId);
        
        $.ajax({
            url: "change-rent-profile?action=delete&rent=" + rentsId + "&account=" + accountId,
            method: "POST",
            dataType: "json",
            data: {
                action: "delete",
                rent_id: rentsId,
                account: accountId,
            },
            success: function(response) {
                console.log(response.action + " " + response.rent + " " + response.account);
            },
            error: function() {
                console.log("Error");
            }
        });
    });
    
    // Отвязать арендатора от лицевого счета
    $(".changes_rent__undo").on("click", function() {
    
        var rentsId = $("input[id=_rents]").val();
        var accountId = $("#_list-account :selected").text();
        alert(accountId);
        
        $.ajax({
            url: "change-rent-profile?action=undo&rent=" + rentsId + "&account=" + accountId,
            method: "POST",
            dataType: "json",
            data: {
                action: "delete",
                rent_id: rentsId,
                account: accountId,
            },
            success: function(response) {
                console.log(response.action + " " + response.rent + " " + response.account);
            },
            error: function() {
                console.log("Error");
            }
        });
    });


')
?>

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
               console.log(response.model);
               $("#content-replace").html(response.data);
            }
        });

    });
')
?>

<?php $this->registerJs('
    $("body").on("beforeSubmit", "form#profile-form", function (e) {
        var yiiform = $(this);
        e.preventDefault();
        alert ("Добавляем нового арендатора");
    });
') ?>

<?= app\widgets\AddRentForm::widget(['add_rent' => $add_rent]); ?>

