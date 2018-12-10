<?php
    
    use yii\helpers\Html;
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
    
    <?= $this->render('data/service-lists', ['pay_services' => $pay_services]) ?>
</div>

<?= $this->render('form/add-paid-request', [
        'new_order' => $new_order, 
        'name_services_array' => $name_services_array]) ?>


<?php
$this->registerCss("
        .navbar-menu {
            box-shadow: none;
        }
");
?>