<?php

    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use kartik\date\DatePicker;

/* 
 * Профиль собсвенника, раздел Платежи
 */

$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $client_info->fullName . ' [' . $account_choosing->account_number . ']';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="profile-page">
        <?= $this->render('page-profile/header', [
                'user_info' => $user_info,
                'client_info' => $client_info,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>

        <div class="payments-profile row">
            <div class="payments-date-block">
                <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
                <div id="panel-search">
                    <?= DatePicker::widget([
                            'name' => 'date_start-period-pay',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => 'С',
                            'layout' => '<span class="input-group-text">Birth Date</span>',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);        
                    ?>
                    <span>-</span>
                    <?= DatePicker::widget([
                            'name' => 'date_end-period-pay',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => 'По',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);        
                    ?>        
                    <?= Html::button('Показать', ['class' => 'btn-show-payment']) ?>
                </div>
                <div class="col-md-12 message-block"></div>
            </div>

            <table class="table managers-table managers-table-in">
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
    </div>
</div>