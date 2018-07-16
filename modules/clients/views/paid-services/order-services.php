<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    
/* 
 * Платные заявки (Заказать услугу)
 */
$this->title = 'Заказать услугу';
?>
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>
    
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
                            'data-toggle' => 'modal',
                            'data-target' => '#add-record',
                            'data-record' => $value->services_id,
                            ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    
</div>


<div id="add-record" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Создать заявку</h4>
            </div>
            <div class="modal-body">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'add-record'
                    ])
                ?>
                
                    <?php // = $form->field($new_order, '')->input('text') ?>

                    <?php // = $form->field($new_order, '')->input('text') ?>
                
                    <?= $form->field($new_order, 'services_phone')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7(999) 999-99-99'])
                            ->input('text', ['placeHolder' => $new_order->getAttributeLabel('services_phone')])->label() ?>
                
                    <?= $form->field($new_order, 'services_comment')
                            ->textarea([
                                'rows' => 10,
                                'placeHolder' => $new_order->getAttributeLabel('services_comment')])->label() ?>
                
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
        var id = $(this).data("record");
        $.post(
            {id: "id"},
        )
        console.log(id);
    })
')
?>