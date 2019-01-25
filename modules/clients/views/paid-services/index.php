<?php
    
    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = Yii::$app->params['site-name'] . 'Услуги';
$this->params['breadcrumbs'][] = 'Услуги';
?>

<div class="paid-requests-page">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
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

<?php /*= $this->render('form/add-paid-request', [
        'new_order' => $new_order, 
        'name_services_array' => $name_services_array]) */ ?>


<?php
$this->registerCss("
        .navbar-menu {
            box-shadow: none;
        }
");
?>