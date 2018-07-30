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
                        'id' => 'add-rent'
                    ])
                ?>
                
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
                
                <?php ActiveForm::end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn__add_rent">Добавить</button>
                <button type="button" class="btn btn-default btn__modal_rent_close" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
    $("#add-rent-modal .btn__add_rent").on("click", function(e) {
        e.preventDefault();
        
        var rentSurname = $("#add-rent-modal .rents-surname").val();
        var rentName = $("#add-rent-modal .rents-name").val();
        var rentSecondName = $("#add-rent-modal .rents-second-name").val();
        var rentMobile = $("#add-rent-modal .rents-mobile").val();
        var rentsEmail = $("#add-rent-modal .rents-email").val();
        var rentsHash = $("#add-rent-modal .rents-hash").val();

        $.ajax({
            url: "' . Url::to(['personal-account/validate-add-rent-form']) . '",
            method: "POST",
            data: {
                "' . Html::getInputName($add_rent, 'rents_surname') . '": rentSurname,
                "' . Html::getInputName($add_rent, 'rents_name') . '": rentName,
                "' . Html::getInputName($add_rent, 'rents_second_name') . '": rentSecondName,
                "' . Html::getInputName($add_rent, 'rents_mobile') . '": rentMobile,
                "' . Html::getInputName($add_rent, 'rents_email') . '": rentsEmail,
                "' . Html::getInputName($add_rent, 'password') . '": rentsHash,
            },
            success: function(response) {
                console.log(response.status);
                console.log(response.errors);
                if (response.status == true) {
                    console.log(rentSurname + " " + rentName + " " + rentSecondName + " " + rentMobile + " " + rentsEmail + " " + rentsHash);
                    $("#add-rent-modal").modal("hide");
                } else {
                    if (typeof response.errors != "undefined") {
                        var errors = response.errors;
                        
                        var parentContainer = $("#add-rent-modal .rents-surname").parent().parent();
                        if (errors.rents_surname) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_surname);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-name").parent();
                        if (errors.rents_name) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_name);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-second-name").parent();
                        if (errors.rents_second_name) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find("help-block").text(errors.rents_second_name);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-mobile").parent();
                        if (errors.rents_mobile) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_mobile);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-email").parent();
                        if (errors.rents_email) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.rents_email);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                        
                        var parentContainer = $("#add-rent-modal .rents-hash").parent();
                        if (errors.password) {
                            $(parentContainer).removeClass("has-success").addClass("has-error");
                            $(parentContainer).find(".help-block").text(errors.password);
                        } else {
                            $(parentContainer).removeClass("has-error").addClass("has-success");
                            $(parentContainer).find(".help-block").text("");
                        }
                    }
                }
            },
        });
    });
')
?>


<?php
$this->registerJs('
    $("#add-rent-modal .btn__modal_rent_close, .btn__modal_close").on("click", function() {
        $("#add-rent-modal input").val("");
        $("#add-rent-modal .modal-body").removeClass("has-error");
        $("#add-rent-modal .modal-body").removeClass("has-success");
        $("#add-rent-modal .form-group").removeClass("has-success"); 
        $("#add-rent-modal .form-group").removeClass("has-error");
        $("#add-rent-modal").find(".help-block").text("");
    });
')
?>
