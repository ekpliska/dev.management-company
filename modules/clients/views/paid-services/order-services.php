<?php
    
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = 'Заказать услугу';
?>
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-info" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('success', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                    
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert alert-error" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('error', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                
        </div>
    <?php endif; ?>         

    
    <?php foreach ($categorys as $category) : ?>
        <div class="col-md-12 text-left" style="margin-bottom: 20px; margin-top: 20px;">
            <?= Html::img('https://placehold.it/20x20?text=i')?> <?= $category->category_name ?>
        </div>
        <div>
            <?php foreach ($category['service'] as $value) : ?>
                <?php if ($value->isPay) : ?>
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


<div id="add-record-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Создать заявку</h4>
            </div>
            <div class="modal-body">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'add-record',
                        
                    ])
                ?>
                
                    <p>Вы находитесь в окне оформления платной услуги, ваша услуга:</p>
                
                    <?= $form->field($new_order, 'services_name_services_id')
                            ->dropDownList($pay_services, [
                                'id' => 'name_services',
                                'class' => 'form-control name_services',
                                'disabled' => true])
                            ->label(false)?>

                    <?= $form->field($new_order, 'services_phone')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7(999) 999-99-99'])
                            ->input('text', ['placeHolder' => $new_order->getAttributeLabel('services_phone'), 'class' => 'form-control phone'])->label() ?>
                
                    <?= $form->field($new_order, 'services_comment')
                            ->textarea([
                                'rows' => 10,
                                'placeHolder' => $new_order->getAttributeLabel('services_comment'),
                                'class' => 'form-control comment'])->label() ?>
                
            </div>
            <div class="modal-footer">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
    $(".new-rec").on("click", function(){
        var idService = $(this).data("record");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
    });
    
    $("body").on("beforeSubmit", "form#add-record", function(e){
        e.preventDefault();

        var name_services = $("#add-record .name_services").val();
        var phone = $("#add-record .phone").val();
        var comment = $("#add-record .comment").val();

        $.ajax({
            url:"' . Url::toRoute(['paid-services/add-record']) . '",
            method: "POST",
            data: {
                "' . Html::getInputName($new_order, "services_name_services_id") . '": name_services,
                "' . Html::getInputName($new_order, "services_phone") . '": phone,  
                "' . Html::getInputName($new_order, "services_comment") . '": comment,
            },
            success: function(response) {
                alert("Ok");
            },
            error: function () {
                alert("Error");
            },
        });
    });    
')
?>