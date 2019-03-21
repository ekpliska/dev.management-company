<?php

    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;

/* 
 * Платежи
 */
$this->title = Yii::$app->params['site-name'] . 'Платежи';
$this->params['breadcrumbs'][] = ['label' => 'Лицевой счет', 'url' => ['personal-account/index']];
$this->params['breadcrumbs'][] = 'Платежи';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<div class="payments-page row">
    <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
    <div class="payments-page__date-block">
        <?= DatePicker::widget([
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'options' => [
                    'placeholder' => 'С',
                ],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]);        
        ?>
        
        &nbsp;&nbsp;&nbsp;
        
        <?= DatePicker::widget([
                'name' => 'date_end-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'options' => [
                    'placeholder' => 'По',
                ],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]);        
        ?>        
        <?= Html::button('Показать', [
                'id' => 'btn-show-payment',
                'class' => 'btn-show-info btn-show-payment',
                'data-account-number' => $account_number,
            ]) 
        ?>
        
        <div class="col-md-12 message-block"></div>
        
    </div>
    
    <table class="table clients-table">
        <thead>
            <tr>
                <th>Расчетный месяц</th>
                <th>Дата платежа</th>
                <th>Тип оплаты</th>
                <th>Сумма платежа</th>
            </tr>
        </thead>
        <tbody id="payments-lists">
            <?= $this->render('data/payments-lists', [
                    'payments_lists' => $payments_lists,
                    'account_number' => $account_number,
            ]) ?>
        </tbody>
    </table>
</div>