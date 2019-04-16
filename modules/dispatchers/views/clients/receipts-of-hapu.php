<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
    use kartik\date\DatePicker;

/* 
 * Профиль собсвенника, раздел Квитанции ЖКУ
 */

$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-dispatcher'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $client_info->fullName . ' [' . $account_choosing->account_number . ']';
?>

<div class="dispatcher-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="profile-page">
        <?= $this->render('page-profile/header', [
                'user_info' => $user_info,
                'client_info' => $client_info,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>

        <div class="profile-content row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 receipts_period">
                <p class="period_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
                <div class="receipts_period-calendar">                    
                    <?= DatePicker::widget([
                            'name' => 'date_start-period',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => 'С',
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ],
                        ]);        
                    ?>
                    <span>-</span>
                    <?= DatePicker::widget([
                            'name' => 'date_end-period',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => 'По',
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
            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12 receipts_body">
                <?php if (!empty($receipts_lists)) : ?> 
                    <?php 
                        // Формируем путь в PDF квитацнии на сервере
                        $file_path = Yii::getAlias('@web') . "receipts/" . $account_number . "/" . $receipts_lists[0]['Расчетный период'] . ".pdf";
                    ?>
                    <?php if (!file_exists($file_path)) : ?>
                        <div class="notice error">
                            <p>
                                <?= "Квитанция {$receipts_lists[0]['Расчетный период']} на сервере не найдена." ?>
                            </p>
                        </div>
                    <?php else : ?>
                        <iframe src="<?= Url::to($file_path, true) ?>" style="width: 100%; height: 670px;" frameborder="0">
                            Ваш браузер не поддерживает фреймы
                        </iframe>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </div>
            
    </div>    
    
</div>