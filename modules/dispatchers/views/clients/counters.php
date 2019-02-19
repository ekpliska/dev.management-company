<?php

    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use kartik\date\DatePicker;

/* 
 * Профиль собсвенника, раздел Приборы устройства
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
        

        <div class="profile-content counters-page row">
            <div class="payments-date-block">
                <div class="col-md-3">
                    <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Месяц и год</p>
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
            </div>    

            <table class="table dispatcher-table dispatcher-table-in table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Приборы учета</th>
                    <th>Дата <br /> следующей поверки</th>
                    <th>Дата <br /> снятия показаний</t</th>
                    <th>Предыдущиее <br /> показания</t</th>
                    <th>Текущее показание</t</th>
                    <th>Расход</th>
                </tr>
                </thead>
                <tbody>
                    <?= $this->render('data/counters-lists', [
                            'counters_lists' => $counters_lists,
                    ]) ?>
                </tbody>
            </table>

        </div>
        
        
        
    </div>
</div>