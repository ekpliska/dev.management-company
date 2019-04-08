<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Данные арендатора
 */
?>
<?php if ($model_rent) : ?>

    <?php $form = ActiveForm::begin([
            'id' => 'rent-form',
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'fieldConfig' => [
                'template' => '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="field">{label}{input}{error}</div></div>',
                'labelOptions' => ['class' => 'label-registration hidden'],
            ],
    ]) ?>
    
    <?= $form->field($model_rent, 'rents_surname')
            ->input('text', ['class' => 'field-input'])
            ->label($model_rent->getAttributeLabel('rents_surname'), ['class' => 'field-label']) ?>
    
    <?= $form->field($model_rent, 'rents_name')
            ->input('text', ['class' => 'field-input'])
            ->label($model_rent->getAttributeLabel('rents_name'), ['class' => 'field-label']) ?>        

    <?= $form->field($model_rent, 'rents_second_name')
            ->input('text', ['class' => 'field-input'])
            ->label($model_rent->getAttributeLabel('rents_second_name'), ['class' => 'field-label']) ?>

    <?= $form->field($model_rent, 'rents_mobile')
            ->input('text', ['class' => 'field-input cell-phone'])
            ->label($model_rent->getAttributeLabel('rents_mobile'), ['class' => 'field-label']) ?>

    <?= $form->field($model_rent, 'rents_mobile_more')
            ->input('text', ['class' => 'field-input house-phone'])
            ->label($model_rent->getAttributeLabel('rents_mobile_more'), ['class' => 'field-label']) ?>

            
    <div class="clearfix"></div>
    
    <div class="rent-tab__btn">
        <?= Html::button('Удалить', ['class' => 'btn btn-small profile-btn-delete']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-small profile-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php endif; ?>
