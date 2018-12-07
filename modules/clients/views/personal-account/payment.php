<?php

    use yii\helpers\Html;

/* 
 * Страница "Платеж" (оплата квитанции)
 */
?>

<div class="payment-page row">
    <div class="col-md-5 col-sm-6 col-xs-12 text-center payment-page_block">
        <?= Html::img($user_info->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $user_info->username]) ?>
        <p class="payment_user-fullname">
            <?= $user_info->fullNameClient ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-home"></i>&nbsp;
            <?= $user_info->getFullAdress($this->context->_choosing) ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-phone"></i>&nbsp;
            <?= $user_info->mobile ?>
        </p>
        <p class="payment_user-info">
            <i class="glyphicon glyphicon-envelope"></i>&nbsp;
            <?= $user_info->email ?>
        </p>
    </div>
    <div class="col-md-7 col-sm-6 col-xs-12 payment-page_block">
        <?= Html::button('Оплатить', ['class' => 'blue-btn add-acc-btn']) ?>
    </div>
</div>

