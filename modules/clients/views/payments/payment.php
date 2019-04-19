<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\Payments;

/* 
 * Страница "Платеж" (оплата квитанции)
 */
$this->title = Yii::$app->params['site-name'] . 'Оплата';
?>
<div class="payment-page row">
    <div class="col-md-5 col-sm-6 col-xs-12 text-center payment-page_block">
        <?= Html::img(Yii::$app->userProfile->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => 'user photo']) ?>
        <p class="payment_user-fullname">
            <?= Yii::$app->userProfile->fullNameClient ?>
        </p>
        <p class="payment_user-info">
            <b>Лицевой счет</b>
            <?= $this->context->_current_account_number ?>
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
        
        <h1 class="payment-page_block__title">
            <?= "Оплата, квитанции {$paiment_info['payment']->receipt_number}"; ?>
        </h1>
        
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
            
            <p class="payment-from_control_summ">Сумма к оплате: <?= isset($sum) ? $sum : '' ?> </p>
            
            <div class="save-btn-group text-center">
                <?php // = Html::submitButton('Оплатить', ['class' => 'btn blue-btn']) ?>
                <?= Html::button('Оплатить', ['class' => 'btn blue-btn', 'id' => 'checkout']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        
        <?php else: ?>
        
            <div class="notice info">
                <p>
                    Платеж <span><?= '#TODO' ?></span> находится в стадии рассмотрения. Срок рассмотрения платежа занимает от 1 до 7 дней. 
                    После подтверждания платежа его статус будет изменен на <span>&laquo;Оплачено&raquo;</span>.
                </p>
            </div>
        
        <?php endif; ?>
        
    </div>
</div>

<?php if ($paiment_info['payment']->payment_status == Payments::NOT_PAID) : ?>
    <?php
        $this->registerJsFile('https://widget.cloudpayments.ru/bundles/cloudpayments', ['position' => $this::POS_HEAD]);
        $this->registerJs("
            var pay = function () {
                var widget = new cp.CloudPayments();
                widget.charge({
                    publicId: '" . Yii::$app->params['payments_system']['publicId'] . "',
                    description: 'Оплата услуг ЖКХ', //назначение
                    amount: " . $sum . ", //сумма
                    currency: 'RUB', //валюта
                    invoiceId: '" . $paiment_info['payment']->unique_number . "', //номер заказа  (необязательно)
                    accountId: '" . Yii::$app->userProfile->email . "', //идентификатор плательщика (необязательно)
                    data: {
                        myProp: '" .$this->context->_current_account_number ."' //произвольный набор параметров
                    }
                },
                function (options) { // success
                    $.ajax({
                        url: 'payment-transaction',
                        method: 'POST',
                        data: {
                            paymentID: '" . $paiment_info['payment']->unique_number . "',
                        },
                        }).done(function(responce) {
                            console.log(responce);
                        });
                },
                function (reason, options) { // fail
                    //действие при неуспешной оплате
                });
            };

            $('#checkout').click(pay);    
        ", yii\web\View::POS_READY);
    ?>
<?php endif; ?>