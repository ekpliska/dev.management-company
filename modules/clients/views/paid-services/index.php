<?php
    
    use yii\helpers\Html;
    use yii\bootstrap4\Modal;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = 'Заказать услугу';
$category_id = 0;
?>

<?php foreach ($pay_services as $key => $service) : ?>

<?php 
    /*
     *  Если ID текущей категории не равен ID предыдущей
     *  то обнуляем сетку бутстрапа и выводим блок услуг с новой категорией 
     */
    if ($category_id != $service['category']['category_id']) : ?>

    <div class="w-100"></div>
    
<?php endif; ?>

<div class="card services-card-preview box-shadow" data-toggle="modal" data-target=".bd-example-modal-lg">
    <div class="services-card-preview-executor-container">
        <h5 class="services-card-preview-executor">
            <?= $service['category']['category_name'] ?>
        </h5> 
    </div>
    <?= Html::img($service['services_image'], ['class' => 'card-img-top services-card-img-top-preview', 'alt' => $service['services_name']]) ?>
    <h5 class="services-card-preview-h">
        <?= $service['services_name'] ?>
    </h5>
    <div class="card-body m-0 p-0 services-card-preview-body">
        <!--  ограничение на 250 символов -->
        <p class="card-text services-card-preview-text mt-0">
            <?= $service['services_description'] ?>
        </p>
        <div class="services-btn-container">
            <span class="cost_service"><?= $service['services_cost'] ?> &#8381;</span>
            <?= Html::button('Заказать', ['class' => 'btn blue-outline-btn-servic mx-auto new-rec', 'data-record' => 0]) ?>
        </div>
        <div class="d-flex justify-content-around align-items-center">
        </div>
    </div>
</div>
<?php $category_id = $service['category']['category_id'] ?>

<?php endforeach; ?>



<?php /*
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>

    <?= AlertsShow::widget() ?>         
    
    <?php foreach ($categorys as $category) : ?>
        <div class="col-md-12 text-left" style="margin-bottom: 20px; margin-top: 20px;">
            <?= Html::img('https://placehold.it/20x20?text=i')?> <?= $category->category_name ?>
        </div>
        <div>
            <?php foreach ($category['service'] as $value) : ?>
                <?php if ($value->isType) : ?>
                    <div class="col-md-2 text-center">
                        <?= Html::img('https://placehold.it/80x80?text=IMAGE', ['class' => 'img-responsive', 'style' => 'width:100%', 'alt' => 'Image']) ?>
                        <?= $value->services_name ?>
                        <br />
                        <?= Html::button('Заказать', [
                            'class' => 'btn btn-success new-rec',
                            'data-record' => $value->services_id,
                            ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    
</div>

*/?>


<?php
    Modal::begin([
        'id' => 'add-record-modal',
        'title' => 'Заявка на платную услугу',
        'closeButton' => [
            'class' => 'close add-acc-modal-close-btn req',
        ],
    ]);
?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-paid-service',
        ]);
    ?>
    
    <?= $form->field($new_order, 'services_name_services_id')
            ->hiddenInput(['id' => 'secret', 'value' => 'hidden value'])
            ->label(false) ?>
                    
    <?= $form->field($new_order, 'services_name_services_id')
            ->dropDownList($pay_services, [
                'id' => 'name_services',
                'class' => 'form-control name_services',
                'disabled' => true])
            ->label(false) ?>

    <?= $form->field($new_order, 'services_phone')
            ->widget(MaskedInput::className(), [
                'mask' => '+7(999) 999-99-99'])
            ->input('text', ['placeHolder' => $new_order->getAttributeLabel('services_phone'), 'class' => 'form-control phone'])->label(false) ?>
                
    <?= $form->field($new_order, 'services_comment')
            ->textarea([
                'rows' => 10,
                'placeHolder' => $new_order->getAttributeLabel('services_comment'),
                'class' => 'form-control comment'])->label(false) ?>
    
    <div class="modal-footer no-border">
        <?= Html::submitButton('Добавить', ['class' => 'btn blue-outline-btn white-btn mx-auto']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 btn__paid_service_close', 'data-dismiss' => 'modal']) ?>
    </div>
    
    <?php ActiveForm::end() ?>    
    
<?php Modal::end(); ?>
    
<?php
$this->registerJs('
    $(".new-rec").on("click", function(){
        var idService = $(this).data("record");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
        $("#secret").val(idService);
    });    
')
?>