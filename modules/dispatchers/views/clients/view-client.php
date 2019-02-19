<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр инофрмации о Собственнике
 */
$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['client-profile/index']];
$this->params['breadcrumbs'][] = $client_info->fullName . ' [' . $account_choosing->account_number . ']';
?>

<div class="dispatcher-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="profile-page">
        <?php
            $form = ActiveForm::begin([
                'id' => 'view-client-info',
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'fieldConfig' => [
                    'template' => '<div class="field has-label"></i>{label}{input}{error}</div>',
                ],
            ]);
        ?>

        <?= $this->render('page-profile/header', [
                'user_info' => $user_info,
                'client_info' => $client_info,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>
    
    
        <div class="profile-content row">

            <!--Собственник-->
            <div class="col-md-6 clients-profile-info_manager">
                <p class="profile-title">
                    Контактные данные
                </p>
                <?= $form->field($client_info, 'clients_surname')
                        ->input('text', ['class' => 'field-input', 'disabled' => true])
                        ->label($client_info->getAttributeLabel('clients_surname'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_name')
                        ->input('text', ['class' => 'field-input', 'disabled' => true])
                        ->label($client_info->getAttributeLabel('clients_name'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_second_name')
                        ->input('text', ['class' => 'field-input', 'disabled' => true])
                        ->label($client_info->getAttributeLabel('clients_second_name'), ['class' => 'field-label']) ?>

                <?= $form->field($user_info, 'user_mobile')
                        ->input('text', ['class' => 'field-input cell-phone', 'disabled' => true])
                        ->label($client_info->getAttributeLabel('user_mobile'), ['class' => 'field-label']) ?>

                <?= $form->field($client_info, 'clients_phone')
                        ->input('text', ['class' => 'field-input house-phone', 'disabled' => true])
                        ->label($client_info->getAttributeLabel('clients_phone'), ['class' => 'field-label']) ?>

                <?= $form->field($user_info, 'user_email')
                        ->input('text', ['class' => 'field-input', 'disabled' => true])
                        ->label($user_info->getAttributeLabel('user_email'), ['class' => 'field-label']) ?>
            </div>


            <!--Арендатор-->
            <div class="col-md-6 rent-profile-info_manager">
                <p class="profile-title">
                    Аредатор
                    <?php if (isset($is_rent)) : ?>
                        <?= Html::checkbox('is_rent', $is_rent ? 'checked' : '', ['id' => 'is_rent']) ?>
                    <?php endif; ?>                                        
                </p>
                <div id="content-replace" class="form-add-rent">
                    <?= $this->render('_form/rent-view', [
                            'form' => $form,
                            'rent_info' => $rent_info]) 
                    ?>
                </div>
            </div>
        </div>

     <?php ActiveForm::end() ?>
    
</div>    
</div>    