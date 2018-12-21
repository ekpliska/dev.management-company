<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use kartik\date\DatePicker;
    use yii\helpers\Url;

/* 
 * Профиль собсвенника, раздел Квитанции ЖКУ
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

        <div class="row">
            <div class="col-md-5 receipts_period">
                <p class="period_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
                <div class="receipts_period-calendar">
                    <span>С</span>
                <?= DatePicker::widget([
                        'name' => 'date_start-period',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => date('d-M-Y'),
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-M-yyyy'
                        ]
                    ]);        
                ?>
                    <span>ПО</span>
                <?= DatePicker::widget([
                        'name' => 'date_end-period',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => date('d-M-Y'),
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-M-yyyy'
                        ]
                    ]);        
                ?>
                </div>
                <?php if (isset($receipts_lists)) : ?>
                    <ul class="list-group receipte-of-lists">
                        <?php foreach ($receipts_lists as $key => $receipt) : ?>
                        <?php 
                            $date = new DateTime($receipt['Дата оплаты']);
                            $str = $date ? "Оплачено {$receipt['Сумма к оплате']}&#8381" : $receipt['Сумма к оплате'].'&#8381"';
                            $url_pdf = '@web/receipts/' . $account_number . '/' . $account_number . '-' . $receipt['Номер квитанции'] . '.pdf';
                        ?>
                            <li class="list-group-item" data-receipt="<?= $receipt['Номер квитанции'] ?>" data-account="<?= $account_number ?>">
                                <p class="receipte-month"><?= $date ? $date->format('F Y') : date('F Y') ?></p>
                                <p class="receipte-number">Квитанция <?= $receipt['Номер квитанции'] ?></p>                                
                                <span class="<?= $date ? 'receipte-btn-pay-ok' : 'receipte-btn-pay' ?>">
                                    <?= $str ?>
                                </span>
                                <a href="<?= Url::to($url_pdf) ?>" class="receipte-btn-dowload" target="_blank">
                                    <i class="glyphicon glyphicon-download-alt"></i>
                                </a>
                            </li>
                            
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-md-7 receipts_body">
            </div>

            <?= Html::button('<i class="glyphicon glyphicon-send"></i>', ['class' => 'send-receipts pull-right']) ?>
        </div>
            
    </div>    
    
</div>

<?php
//$this->registerJS("
//    $('.list-group-item').on('click', function(){
//        var liItem = $(this).data('receipt');
//        alert(location.origin);
//        $('#frame-receipt').attr('src', '" . Url::to('@web/receipts/' . $account_number . '/' . $account_number . "-' + liItem + '.pdf' +'" , true) . "');
//    });
//    
//");
?>