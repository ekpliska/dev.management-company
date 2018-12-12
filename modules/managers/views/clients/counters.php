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
$this->params['breadcrumbs'][] = $client_info->fullName;
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
        

<div class="counters-page row">
    <div class="payments-date-block">
        <div class="col-md-3">
            <p class="payment_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
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
    
    <table class="table requests-table payment-table">
        <thead>
        <tr>
            <th></th>
            <th>Приборы учета</th>
            <th>Дата <br /> следующей поверки</th>
            <th>Дата <br /> снятия показаний</t</th>
            <th>Предыдущиее <br /> показания</t</th>
            <th>Дата снятия<br /> текущих показаний</t</th>
            <th>Текущее показание</t</th>
            <th>Расход</th>
        </tr>
        </thead>
        <tr class="block-edit-reading">
            <td>
                <?= Html::img('/images/counters/hold-water.svg', ['alt' => '']) ?>
            </td>
            <td>
                <p class="counter-name">#TODO</p>
                <p class="counter-number">#TODO</p>
            </td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>
                <span>Ввод показаний заблокирован</span>
            </td>
            <td>
                <?= Html::a('Заказать поверку', ['/'], ['class' => 'create-send-request']) ?>
            </td>
            <td>Sample text</td>
        </tr>
        <tr>
            <td>
                <?= Html::img('/images/counters/hot-water.svg', ['alt' => '']) ?>
            </td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>
                <?= Html::input('text', 'reading-input', '', ['class' => 'reading-input']) ?>
            </td>
            <td>Sample text</td>
        </tr>
        <tr>
            <td>
                <?= Html::img('/images/counters/electric-meter.svg', ['alt' => '']) ?>
            </td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
        </tr>
        <tr>
            <td>
                <?= Html::img('/images/counters/heating-meter.svg', ['alt' => '']) ?>
            </td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
        </tr>
        <tr>
            <td>
                <?= Html::img('/images/counters/heat-distributor.svg', ['alt' => '']) ?>
            </td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
            <td>Sample text</td>
        </tr>
    </table>        
    
    <div class="col-md-12 counters-message">
        <p>#FORMS</p>
    </div>
    
</div>        
        
        
        
    </div>
</div>