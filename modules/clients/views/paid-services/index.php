<?php
    
    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = 'Заказать услугу';
?>

<div id="services-list" class="row">
    <?= $this->render('data/service-lists', ['pay_services' => $pay_services]) ?>
</div>

<?= $this->render('form/add-paid-request', [
        'new_order' => $new_order, 
        'name_services_array' => $name_services_array]) ?>