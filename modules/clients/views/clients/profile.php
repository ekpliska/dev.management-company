<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

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
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Контактные данные</strong>                    
                </div>
                <div class="text-right">
                    <?= $form->field($client, 'is_rent')->checkbox(['id' => 'addRent']) ?>
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
        </div>

   
    <div class="col-md-4">        
        <div class="panel panel-default panel__add_rent">
            <div class="panel-heading"><strong>Контактные данные арендатора</strong></div>
            <div class="panel-body add_rent">
                
                <?= $form->field($clients_rent, 'surnamne')->input('text', ['placeHolder' => $clients_rent->getAttributeLabel('surnamne')])->label(true) ?>
                
                <?= $form->field($clients_rent, 'name')->input('text', ['placeHolder' => $clients_rent->getAttributeLabel('name')])->label(true) ?>
                
                <?= $form->field($clients_rent, 'secondname')->input('text', ['placeHolder' => $clients_rent->getAttributeLabel('secondname')])->label(true) ?>
                
                <?= $form->field($clients_rent, 'mobile')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7(999) 999-99-99'])
                        ->input('text', ['placeHolder' => $clients_rent->getAttributeLabel('mobile')])->label(true) ?>
                
                <?= $form->field($clients_rent, 'email')->input('text', ['placeHolder' => $clients_rent->getAttributeLabel('email')])->label(true) ?>
                
                <?= $form->field($clients_rent, 'password')->input('password', ['placeHolder' => $clients_rent->getAttributeLabel('password')])->label(true) ?>
                
            </div>
            <div class="panel-body info_rent">
                
                <p>Фамилия Имя Отчество</p>
                <?= $user->personalAccount->rent->rents_surname ?>
                <?= $user->personalAccount->rent->rents_name ?>
                <?= $user->personalAccount->rent->rents_second_name ?>
                
                <p>Мобильный телефон</p>
                <?= $user->personalAccount->rent->rents_mobile ?>
                
                <p>Электронная почта</p>
                <?php // = $user->personalAccount->rent->user_email ?>
                
            </div>
        </div>
    </div>
    
    <?php if ($client->is_rent) : ?>
        <div class="col-md-4">        
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Контактные данные арендатора</strong></div>
                <div class="panel-body info_rent">

                    <p>Фамилия Имя Отчество</p>
                    <?= $user->personalAccount->rent->rents_surname ?>
                    <?= $user->personalAccount->rent->rents_name ?>
                    <?= $user->personalAccount->rent->rents_second_name ?>

                    <p>Мобильный телефон</p>
                    <?= $user->personalAccount->rent->rents_mobile ?>

                    <p>Электронная почта</p>
                    <?php // = $user->personalAccount->rent->user_email ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="col-md-12 text-right">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
        <?php ActiveForm::end(); ?>        
