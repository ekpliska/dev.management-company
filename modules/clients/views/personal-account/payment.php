<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;

/* 
 * Страница "Платеж" (оплата квитанции)
 */
$this->title = Yii::$app->params['site-name'] . 'Оплата';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = ['label' => 'Платежи', 'url' => ['personal-account/payments']];
$this->params['breadcrumbs'][] = 'Оплата' . ' #TODO';    
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
        <div class="field">
            <label for="user_mobile" class="field-label">Управляющая компания</label>
            <?= Html::input('text', 'organization-name', "{$organization_info->name}", ['class' => 'field-input', 'readonly' => true]) ?>
        </div>
        
        <?php
            $form = ActiveForm::begin([
                'id' => 'payment-from',
                'method' => 'GET',
                'fieldConfig' => [
                    'template' => '<div class="field">{label}{input}{error}</div>',
                ], 
            ])
        ?>
        
        <?= $form->field($model, 'order_id')->input('text', ['class' => 'field-input'])->label($model->getAttributeLabel('order_id'), ['class' => 'field-label']) ?>
        <?= $form->field($model, 'amount')->input('text', ['class' => 'field-input'])->label($model->getAttributeLabel('amount'), ['class' => 'field-label']) ?>
        
        <div class="save-btn-group text-center">
            <?= Html::submitButton('Оплатить', ['class' => 'btn blue-btn']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
        
        
        
        
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

