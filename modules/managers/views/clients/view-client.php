<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use yii\widgets\MaskedInput;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Просмотр инофрмации о Собственнике
 */
$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $client_info->fullName;
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="profile-page">
        <?php
            $form = ActiveForm::begin([
                'id' => 'view-client-info',
                'method' => 'POST',
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'data-pjax' => true,
                ],
                'fieldConfig' => [
                    'template' => '<div class="field has-label"></i>{label}{input}{error}</div>',
                ],
            ]);
        ?>

        <?= $this->render('page-profile/header', [
                'form' => $form,
                'user_info' => $user_info,
                'client_info' => $client_info,
                'add_rent' => $add_rent,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>
    
    
        <div class="row">

            <!--Собственник-->
            <div class="col-md-6 clients-profile-info_manager">
                <p class="profile-title">
                    Контактные данные
                </p>
                <?= $form->field($client_info, 'clients_surname')
                        ->input('text', ['class' => 'field-input'])
                        ->label($client_info->getAttributeLabel('clients_surname'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_name')
                        ->input('text', ['class' => 'field-input'])
                        ->label($client_info->getAttributeLabel('clients_name'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_second_name')
                        ->input('text', ['class' => 'field-input'])
                        ->label($client_info->getAttributeLabel('clients_second_name'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_mobile')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])
                        ->input('text', ['class' => 'field-input'])
                        ->label($client_info->getAttributeLabel('clients_mobile'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_phone')
                        ->widget(MaskedInput::className(), [
                            'mask' => '+7 (999) 999-99-99'])
                        ->input('text', ['class' => 'field-input'])
                        ->label($client_info->getAttributeLabel('clients_phone'), ['class' => 'field-label']) ?>

                <?= $form->field($user_info, 'user_email')
                        ->input('text', ['class' => 'field-input'])
                        ->label($user_info->getAttributeLabel('user_email'), ['class' => 'field-label']) ?>
            </div>


            <!--Арендатор-->
            <div class="col-md-6 rent-profile-info_manager">
                <p class="profile-title">
                    Аредатор
                </p>
                <div id="content-replace" class="form-add-rent">
                        <?= $this->render('_form/rent-view', [
                                'form' => $form,
                                'rent_info' => $rent_info]) 
                        ?>
                </div>
            </div>
        </div>

        <div class="spam-agree-txt text-center">
            <?= $form->field($user_info, 'user_check_email', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 
            <div class="save-btn-group mx-auto">
                <div class="text-center">
                    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
                </div>
            </div>

        </div>
     <?php ActiveForm::end() ?>
    
</div>    
</div>    