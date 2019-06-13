<?php

    use yii\helpers\Html;
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
            Оплата, квитанции <?= isset($paiment_info->receipt_number) ? $paiment_info->receipt_number : null ?>
        </h1>
        <?php if ($paiment_info->payment_status == Payments::NOT_PAID) : // Если платеж существует и его статус "Не оплачено" ?>
            <div class="field">
                <label for="user_mobile" class="field-label">Управляющая компания</label>
                <?= Html::input('text', 'organization-name', "{$organization_info->name}", ['class' => 'field-input', 'readonly' => true]) ?>
            </div>
            <p class="payment-from_control_summ">Сумма к оплате: <?= isset($paiment_info->payment_sum) ? $paiment_info->payment_sum : '' ?> </p>
            
            <div class="save-btn-group text-center">
                <?php // = Html::submitButton('Оплатить', ['class' => 'btn blue-btn']) ?>
                <?= Html::button('Оплатить', ['class' => 'btn blue-btn', 'id' => 'checkout']) ?>
            </div>

        <?php elseif ($paiment_info->payment_status == Payments::YES_PAID) : // Если платеж существует и его статус "Оплачено" ?>
            <div class="notice info">
                <p>
                    Платеж за расчетный период <span><?= $paiment_info->receipt_period ?></span>, на сумму <span><?= $paiment_info->payment_sum ?></span> 
                    <span>&laquo;Оплачен&raquo;</span>.
                </p>
            </div>
            
            <?= Html::a('Вернуться к моим платежам', ['payments/index'], ['class' => 'go-back-page']) ?>
        
        <?php elseif ($paiment_info == 'error') : ?>
            
            <div class="notice info">
                <p>
                    Приносим свои извинения, совершить оплату в данный момент невозможно.
                </p>
            </div>
            
            <?= Html::a('Вернуться к моим платежам', ['payments/index'], ['class' => 'go-back-page']) ?>
            
        <?php endif; ?>
        
    </div>
</div>

<?php if ($paiment_info->payment_status == Payments::NOT_PAID) : ?>
    <?php
        $this->registerJsFile('https://widget.cloudpayments.ru/bundles/cloudpayments', ['position' => $this::POS_HEAD]);
        $this->registerJs("
            var pay = function () {
                var widget = new cp.CloudPayments();
                
                var receipt = {
                    Items: [//товарные позиции
                         {
                            label: '" . $description . "', //наименование товара
                            price: " . $paiment_info->payment_sum . ", //цена
                            quantity: 1.00, //количество
                            amount: " . $paiment_info->payment_sum . ", //сумма
                            vat: " . Yii::$app->paymentSystem->vat . ", //ставка НДС
                            method: " . Yii::$app->paymentSystem->method . ", // тег-1214 признак способа расчета - признак способа расчета
                            object: " . Yii::$app->paymentSystem->object . ", // тег-1212 признак предмета расчета - признак предмета товара, работы, услуги, платежа, выплаты, иного предмета расчета
                        }
                    ],
                    taxationSystem: 0, //система налогообложения; необязательный, если у вас одна система налогообложения
                    email: '" . Yii::$app->userProfile->email . "', //e-mail покупателя, если нужно отправить письмо с чеком
                    phone: '" . Yii::$app->userProfile->mobile . "', //телефон покупателя в любом формате, если нужно отправить сообщение со ссылкой на чек
                    isBso: false, //чек является бланком строгой отчётности
                    amounts:
                    {
                        electronic: " . $paiment_info->payment_sum . ", // Сумма оплаты электронными деньгами
                        advancePayment: 0.00, // Сумма из предоплаты (зачетом аванса) (2 знака после запятой)
                        credit: 0.00, // Сумма постоплатой(в кредит) (2 знака после запятой)
                        provision: 0.00 // Сумма оплаты встречным предоставлением (сертификаты, др. мат.ценности) (2 знака после запятой)
                    }
                };
                
                var data = { //содержимое элемента data
                    'cloudPayments': {
                        'customerReceipt': receipt, //онлайн-чек
                    }
                }
                
                widget.charge({
                    publicId: '" . $public_id . "',
                    description: '" . $description . "', //назначение
                    amount: " . $paiment_info->payment_sum . ", //сумма
                    currency: 'RUB', //валюта
                    invoiceId: '" . $paiment_info->unique_number . "', //номер заказа или счета 
                    accountId: '" . $this->context->_current_account_number . "', // идентификатор плательщика (необязательно)
                    email: '" . Yii::$app->userProfile->email . "', // E-mail адрес пользователя
                    requireEmail: true,
                    data: data
                },
                function (options) { // success
                    $.ajax({
                        url: 'payment-transaction',
                        method: 'POST',
                        data: {
                            paymentID: '" . $paiment_info->unique_number . "',
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