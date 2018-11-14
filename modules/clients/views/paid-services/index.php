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
    
<?php
$this->registerJs('
    $(".new-rec").on("click", function(){
        var idService = $(this).data("service");
        var idCategory = $(this).data("service-cat");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
        $("#secret-name").val(idService);
        $("#secret-cat").val(idCategory);
    });    
')
?>