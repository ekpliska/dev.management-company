<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
    
/*
 * Профиль пользователя
 */
$this->title = 'Профиль абонента';
?>


<?php
    $form = ActiveForm::begin([
        'id' => 'profile-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ])
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
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>                    
                </div>
                <div class="text-right">
                    <?php // = $form->field($client, 'is_rent')->checkbox(['id' => 'addRent']) ?>
                </div>
            </div>            
            
            <?php
                $this->registerJs('
                    $("#addRent").change(function(e) {
                        if (!this.checked && "' . $rent->id . '") {
                            $("#delete_rent").modal("show");
                            $(".delete_yes").on("click", function(e) {
                                    $.ajax({
                                        url:"' . Url::toRoute(['clients/delete-rent']) . '",
                                        method: "POST",
                                        data: {
                                            rent_id: "' . $rent->id . '",
                                            status: "' . false . '",
                                        },
                                        success: function(data){
                                            console.log(data);
                                        }
                                    });
                            });

                            $(".undo_yes").on("click", function(e) {
                                    $.ajax({
                                        url:"' . Url::toRoute(['clients/undo-rent']) . '",
                                        method: "POST",
                                        data: {
                                            rent_id: "' . $rent->id . '",
                                            status: "' . false . '",
                                        },
                                        success: function(data){
                                            console.log(data);
                                        }
                                    });
                            });

                            $(".delete_no").on("click", function(e) {
                                $("#addRent").prop("checked", true);
                            });

                        }
                        
                        if (this.checked && !"' . $rent->id . '") {
                            // console.log("new rent");
//                            $.ajax({
//                                url:"' . Url::toRoute(['clients/add-rent']) . '",
//                                method: "POST",
//                                data: {},
//                                success: function(data){
//                                    console.log(data);
//                                }
//                            });
                        }
                    });
                ');
            ?>
            
           
            
            <div class="panel-body">
                <p>Как вас зовут?</p>                
                <?= $user->client->clients_surname ?>
                <?= $user->client->clients_name ?>
                <?= $user->client->clients_second_name ?>
                
                <p>Домашний телефон</p>
                <?= $user->client->clients_phone ?>
                
                <p>Мобильный телефон</p>
                <?= $user->client->clients_mobile ?>
                
                <p>Электронная почта</p>
                <?= $user->user_email ?>
                
                <p>Номер лицевого счета</p>
                <?= $user->client->personalAccount->account_number ?>
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
                <?= $form->field($user, 'user_check_email')->checkbox() ?>                    
            </div>            
        </div>        
    </div>

   
    <div class="col-md-4">        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Контактные данные арендатора</strong></div>
            <div class="panel-body info_rent">
                <?php if ($is_rent && isset($rent)) : ?>
                    
                    <?= $form->field($rent, 'rents_surname')->input('text')->label(true) ?>
                
                    <?= $form->field($rent, 'rents_name')->input('text')->label(true) ?>
                
                    <?= $form->field($rent, 'rents_second_name')->input('text')->label(true) ?>
                    
                    <?= $form->field($rent, 'rents_mobile')->input('text')->label(true) ?>
                
                    <?= $form->field($rent, 'rents_email')->input('text')->label(true) ?>
                    
                <?php else: ?>
                    <?= 'У собственника арендатора нет' ?>                    
                <?php endif; ?>
                    
                </div>
            </div>
        </div>        
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
    
    <?php if (!$is_rent) : ?>
        <div class="col-md-4">        
            <div class="panel panel-default add__rent">
                <div class="panel-heading"><strong>Добавить арендатора</strong></div>
                <div class="panel-body info_rent">
                    <?php Pjax::begin(['enablePushState' => false]) ?>
                        <a href="<?= Url::to(['clients/add-form-rent', 'client_id' => $client->id]) ?>">Добавить</a>
                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<?php /* Модальное окно на подтверждение удаления аредатора */ ?>
<div class="modal fade" id="delete_rent" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Подтверждение удаления
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Какое действие вы хотите совершить с учетной записью арендатора? 
                    <?php if ($is_rent && isset($rent)) : ?>
                        Арендатор: <strong><?= $rent->fullName ?></strong>
                    <?php endif; ?>
                    <br />
                    *** Внимание, при удалении арендатора так же будет удалена его учетная запись на портале
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_yes" data-dismiss="modal">Удалить</button>
                <button class="btn btn-success undo_yes" data-dismiss="modal">Отвязать от лицевого счета</button>
                <button class="btn btn-primary delete_no" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div> 
