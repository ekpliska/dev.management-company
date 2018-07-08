<?php 
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
    use yii\helpers\Url;

?>
<?= 'id ' . $client_id ?>
    <?php
        $form_rent = ActiveForm::begin([
            'id' => 'add-rent',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            // 'action' => ['clients/add-rent']
            // 'validationUrl'=>['clients/form-validate'],
        ])
    ?>
        <?= $form_rent->field($rent_new, 'rents_surname')->input('text', ['class' => 'in1', 'placeHolder' => $rent_new->getAttributeLabel('rents_surname')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_name')->input('text', ['class' => 'in2', 'placeHolder' => $rent_new->getAttributeLabel('rents_name')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_second_name')->input('text', ['class' => 'in3', 'placeHolder' => $rent_new->getAttributeLabel('rents_second_name')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_mobile')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7(999) 999-99-99'])
                ->input('text', ['class' => 'in4', 'placeHolder' => $rent_new->getAttributeLabel('rents_mobile')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_email')->input('text', ['class' => 'in5', 'placeHolder' => $rent_new->getAttributeLabel('rents_email')])->label() ?>

        <?= $form_rent->field($rent_new, 'password')->input('password', ['class' => 'in6', 'placeHolder' => $rent_new->getAttributeLabel('password')])->label() ?>

        <?php // = Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>



            <?php $this->registerJs('
                $("body").on("beforeSubmit", "form#profile-form", function (e) {
                    e.preventDefault();
                    alert ("test");
                    var rents_surname  = $("#add-rent .in1").val();
                    var rents_name        = $("#add-rent .in2").val();
                    var rents_second_name        = $("#add-rent .in3").val();
                    var rents_mobile = $("#add-rent .in4").val();
                    var rents_email        = $("#add-rent .in5").val();
                    var password        = $("#add-rent .in6").val();

                                    $.ajax({
                                        url:"' . Url::toRoute(['clients/add-rent']) . '",
                                        method: "POST",
                                        data: {
                                            "' . Html::getInputName($rent_new, "rents_surname") . '": rents_surname,
                                            "' . Html::getInputName($rent_new, "rents_name") . '": rents_name,  
                                            "' . Html::getInputName($rent_new, "rents_second_name") . '": rents_second_name,
                                            "' . Html::getInputName($rent_new, "rents_mobile") . '": rents_mobile,
                                            "' . Html::getInputName($rent_new, "rents_email") . '": rents_email,
                                            "' . Html::getInputName($rent_new, "password") . '": password,
                                            client_id: "' . $client_id . '",
                                        },
                                        success: function(data){
                                            console.log(data);
                                        }
                                    });


                    });
                    ') ?>
