<?php
    
    use yii\bootstrap\Modal;
    use app\modules\clients\widgets\AlertsShow;
    use app\modules\clients\widgets\SubBarPaidService;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = Yii::$app->params['site-name'] . 'Услуги';
?>

<div class="paid-requests-page">
    
    <?= SubBarPaidService::widget() ?>    
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/service-lists', ['pay_services' => $pay_services]) ?>
</div>


<?php
    Modal::begin([
        'id' => 'add-paid-request-modal',
        'header' => 'Заявка на платную услугу',
        'closeButton' => [
            'class' => 'close modal-close-btn btn__paid_service_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>
    
<?php Modal::end(); ?>

<?php
$this->registerCss("
        .navbar-menu {
            box-shadow: none;
        }
");
?>