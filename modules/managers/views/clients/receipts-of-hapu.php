<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use kartik\date\DatePicker;
    use yii\helpers\Url;
    use app\modules\managers\widgets\ModalWindowsManager;

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
                            'value' => date('d-m-Y'),
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ],
                        ]);        
                    ?>
                        <span>ПО</span>
                    <?= DatePicker::widget([
                            'name' => 'date_end-period',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => date('d-m-Y'),
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);        
                    ?>
                    <?= Html::button('<i class="glyphicon glyphicon-search"></i>', ['id' => 'get-receipts', 'class' => 'btn-send-request']) ?>
                </div>
                <div class="message-block"></div>
                <div id="receipts-lists">
                    <?= $this->render('data/receipts-lists', [
                            'receipts_lists' => $receipts_lists,
                            'account_number' => $account_number
                    ]) ?>
                </div>
            </div>
            <div class="col-md-7 receipts_body">
            </div>

            <?= Html::button('<i class="glyphicon glyphicon-send"></i>', ['class' => 'send-receipts pull-right']) ?>
        </div>
            
    </div>    
    
</div>