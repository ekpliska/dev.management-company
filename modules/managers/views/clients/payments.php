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
                'form' => $form,
                'user_info' => $user_info,
                'client_info' => $client_info,
                'add_rent' => $add_rent,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>

        <div class="payments-profile row">
            <div class="payments-date-block">
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
            
            </div>

            <table class="table managers-table managers-table-in">
                <thead>
                <tr>
                    <th>Дата платежа</th>
                    <th>Сумма платежа</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tr>
                    <td>Sample text</td>
                    <td>Sample text</td>
                    <td>
                        <span class="payment-debt">Задолженность</span>
                    </td>
                </tr>
                <tr>
                    <td>Sample text</td>
                    <td>Sample text</td>
                    <td>
                        <span class="payment-ok"><i class="glyphicon glyphicon-ok"></i> Оплачено</span>
                    </td>
                </tr>
                <tr>
                    <td>Sample text</td>
                    <td>Sample text</td>
                    <td>Sample text</td>
                </tr>
            </table>
            
        </div>
    </div>
</div>