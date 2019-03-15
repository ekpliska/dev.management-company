<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;

/* 
 * Страница "Платеж" (оплата квитанции)
 */
$this->title = Yii::$app->params['site-name'] . 'Оплата';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = ['label' => 'Квитанции', 'url' => ['personal-account/receipts-of-hapu']];
$this->params['breadcrumbs'][] = "Оплата, квитанции {$paiment_info['payment']->receipt_number}";    
?>
<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<div class="payment-page row">
    <div class="col-md-5 col-sm-6 col-xs-12 text-center payment-page_block">
        <?= Html::img(Yii::$app->userProfile->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => 'user photo']) ?>
        <p class="payment_user-fullname">
            <?= Yii::$app->userProfile->fullNameClient ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-home"></i>&nbsp;
            <?= Yii::$app->userProfile->getFullAdress($this->context->_current_account_id) ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-phone"></i>&nbsp;
            <?= Yii::$app->userProfile->mobile ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-envelope"></i>&nbsp;
            <?= Yii::$app->userProfile->email ?>
        </p>
    </div>
    <div class="col-md-7 col-sm-6 col-xs-12 payment-page_block">
        
        <?php if (isset($model)) : // Если модель для платежа передана ?>
        
            <div class="field">
                <label for="user_mobile" class="field-label">Управляющая компания</label>
                <?= Html::input('text', 'organization-name', "{$organization_info->name}", ['class' => 'field-input', 'readonly' => true]) ?>
            </div>
            <?php
                $form = ActiveForm::begin([
                    'id' => 'payment-from',
                    'fieldConfig' => [
                        'template' => '<div class="field">{label}{input}{error}</div>',
                    ], 
                ])
            ?>
        

            <?= $form->field($model, 'payment_sum')
                    ->widget(MaskedInput::className(), [
                        'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => '',
                            'radixPoint' => '.',
                            'autoGroup' => false]])
                    ->input('text', ['class' => 'field-input'])
                    ->label($model->getAttributeLabel('payment_sum'), ['class' => 'field-label']) ?>

            <p class="payment-from_control_summ">Сумма к оплате: <?= isset($sum) ? $sum : '' ?> </p>
            
            <div class="save-btn-group text-center">
                <?= Html::submitButton('Оплатить', ['class' => 'btn blue-btn']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        
        <?php else: ?>
        
            <div>
                <?php
                 var_dump($paiment_info);
                ?>
            </div>
        
        <?php endif; ?>
        
        
        <?php /*
        <?= Html::beginForm([
            'id' => 'payment-form',
        ]) ?>
            <?= Html::input('text') ?>
        <?= Html::endForm(); ?>
        <?= Html::button('Оплатить', ['class' => 'blue-btn add-acc-btn']) ?>
         * 
         */ ?>
    </div>
</div>

