<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

$this->title = 'Профиль абонента';
?>



<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>                    
                </div>
                <div class="text-right">
                    <label><input type="checkbox" value="rent" id="addRent"> Арендатор</label>                    
                </div>
            </div>
            <div class="panel-body">
                
                <p>Как вас зовут?</p>                
                <?= $user->personalAccount->client->clients_surname ?>
                <?= $user->personalAccount->client->clients_name ?>
                <?= $user->personalAccount->client->clients_second_name ?>
                
                <p>Домашний телефон</p>
                <?= $user->personalAccount->client->clients_phone ?>
                
                <p>Мобильный телефон</p>
                <?= $user->personalAccount->client->clients_mobile ?>
                
                <p>Электронная почта</p>
                <?= $user->user_email ?>
                
                <p>Номер лицевого счета</p>
                <?= $user->personalAccount->account_number ?>
                
            </div>
        </div>
    </div>
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
        <div class="col-md-4">        
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Фотография</strong></div>
                <div class="panel-body">
                    <div class="text-center">
                        <?php if (empty($user->user_photo)) : ?>
                            <?= Html::img('/images/no-avatar.jpg', ['class' => 'img-circle', 'alt' => 'no-avatar', 'width' => 150]) ?>
                        <?php else: ?>
                            <?= Html::img($user->user_photo, ['class' => 'img-circle', 'alt' => $user->user_login, 'width' => 150]) ?>
                        <?php endif; ?>
                    </div>
                    
                    <?= $form->field($user, 'user_photo')->input('file') ?>
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

    
    <div class="col-md-4">        
        <div class="panel panel-default panel__add_rent">
            <div class="panel-heading"><strong>Контактные данные арендатора</strong></div>
            <div class="panel-body">Panel Content</div>
        </div>
    </div>
    
    <div class="col-md-12 text-right">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>        
    </div>
</div>
