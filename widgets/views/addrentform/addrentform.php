<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
/*
 * Модальное окно
 * Форма - Добавить арендатора
 */
?>
<div id="add-rent-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Новый арендатор</h4>
            </div>
            <div class="modal-body">
                <?php
                    $form_add_rent = ActiveForm::begin([
                        'id' => 'add-rent',
                        'enableAjaxValidation' => true,
                        'validationUrl' => ['profile/validate-add-rent-form'],
                    ])
                ?>
                
                    <?= $form_add_rent->field($add_rent, 'account_id')->input('text', ['id' => '_personal-account']) ?>
                
                    <?= $form_add_rent->field($add_rent, 'rents_surname')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_surname'),
                                'class' => 'form-control rents-surname'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_name')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_name'),
                                'class' => 'form-control rents-name'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_second_name')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_second_name'),
                                'class' => 'form-control rents-second-name'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_mobile')
                            ->widget(MaskedInput::className(), [
                                'mask' => '+7(999) 999-99-99'])
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_mobile'),
                                'class' => 'form-control rents-mobile'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'rents_email')
                            ->input('text', [
                                'placeHolder' => $add_rent->getAttributeLabel('rents_email'),
                                'class' => 'form-control rents-email'])
                            ->label() ?>

                    <?= $form_add_rent->field($add_rent, 'password')
                            ->input('password', [
                                'placeHolder' => $add_rent->getAttributeLabel('password'),
                                'class' => 'form-control rents-hash'])
                            ->label() ?>                                   
                
                
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-success btn__add_rent']) ?>
                <button type="button" class="btn btn-default btn__modal_rent_close" data-dismiss="modal">Отмена</button>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<?php
/* Получаем ID вбранного лицевого счета */
$this->registerJs('
    $("#add-rent-modal").on("show.bs.modal", function(e) {
        var accountId = $("#_list-account :selected").text();
        $("#_personal-account").val(accountId);
        $(".btn__add_rent", this).data("accountId", accountId);
    });
')    
?>

<?php
$this->registerJs('
    $("#add-rent").on("beforeSubmit", function() {
        var addRentForm = $(this);
        $.ajax({
            url: "add-new-rent",
            type: "POST",
            data: addRentForm.serializeArray(),
            succeess: function(response) {
                if (response.status === false) {
                    console.log("Error when data try to saved (add rent form)");
                }
            },
            error: function() {
                console.log("Error #1 when data try to saved (add rent form)");
            },
        });
    });
')
?>

<?php
//$this->registerJs('
//    $("#add-rent").on("beforeSubmit", function() {
//        var addRentForm = $(this);
//        $.ajax({
//            url: "add-new-rent",
//            type: "POST",
//            data: addRentForm.serializeArray(),
//            
//        })
//        .done(function(data) {
//            if (data.success === true) {
//                console.log("data saved");
//            } else {
//                console.log("error when data try to saved");
//            }
//        })
//        .fail(function() {
//            console.log("123");
//        })
//    });
//')
?>