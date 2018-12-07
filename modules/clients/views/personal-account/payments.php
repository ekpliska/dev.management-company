<?php

    use kartik\date\DatePicker;
    use yii\helpers\Html;

/* 
 * Платежи
 */

?>
<div class="payments-page row">
    <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
    <div class="col-md-3 date-block">
        <span>С</span>
        <?= DatePicker::widget([
                'name' => 'date_start-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('d-M-Y'),
                'layout' => '<span class="input-group-text">Birth Date</span>',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy'
                ]
            ]);        
        ?>
        
    </div>
    <div class="col-md-3 date-block">
        <span>ПО</span>
        <?= DatePicker::widget([
                'name' => 'date_end-period-pay',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('d-M-Y'),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy'
                ]
            ]);        
        ?>        
    </div>
    <div class="col-md-6">
        <?= Html::button('Показать', ['class' => 'btn-show-payment']) ?>        
    </div>
    

<table class="table requests-table payment-table">
    <thead>
    <tr>
        <th>#TODO</th>
        <th>#TODO</th>
        <th>#TODO</th>
        <th>#TODO</th>
    </tr>
    </thead>
    <tr>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>
            <?= Html::a('Оплатить', ['personal-account/payment'], ['class' => 'payment_btn-pay']) ?>
        </td>
    </tr>
    <tr>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>
            <span class="paument-ok"><i class="glyphicon glyphicon-ok"></i> Оплачено</span>
        </td>
    </tr>
    <tr>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
    </tr>
    <tr>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
        <td>Sample text</td>
    </tr>
</table>        
        
        
</div>