<?php 
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use yii\helpers\Url;
?>


<?php Pjax::begin(['id' => 'new_rent']); ?>
    <?php
        $form_rent = ActiveForm::begin([
            'id' => 'add-rent',
            'action' => 'clients/add-rent',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'options' => [
                'data-pjax' => true,
            ],
        ])
    ?>
        <?= $form_rent->field($rent_new, 'rents_surname')->input('text', ['placeHolder' => $rent_new->getAttributeLabel('rents_surname')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_name')->input('text', ['placeHolder' => $rent_new->getAttributeLabel('rents_name')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_second_name')->input('text', ['placeHolder' => $rent_new->getAttributeLabel('rents_second_name')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_mobile')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7(999) 999-99-99'])
                ->input('text', ['placeHolder' => $rent_new->getAttributeLabel('rents_mobile')])->label() ?>

        <?= $form_rent->field($rent_new, 'rents_email')->input('text', ['placeHolder' => $rent_new->getAttributeLabel('rents_email')])->label() ?>

        <?= $form_rent->field($rent_new, 'password')->input('password', ['placeHolder' => $rent_new->getAttributeLabel('password')])->label() ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>

<?php Pjax::end(); ?> 


<?php /*
<?php Pjax::begin(); ?>
<?= Html::beginForm(['clients/test'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
    <?= Html::input('text', 'rents_surname', Yii::$app->request->post('rents_surname'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'rents_name', Yii::$app->request->post('rents_name'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'rents_second_name', Yii::$app->request->post('rents_second_name'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'rents_mobile', Yii::$app->request->post('rents_mobile'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'rents_email', Yii::$app->request->post('rents_email'), ['class' => 'form-control']) ?>
    <?= Html::submitButton('save', ['class' => 'btn btn-primary', 'name' => 'hash-button']) ?>
<?= Html::endForm() ?>
<h3><?= $stringHash ?></h3>
<?php Pjax::end(); ?>
*/
?>
