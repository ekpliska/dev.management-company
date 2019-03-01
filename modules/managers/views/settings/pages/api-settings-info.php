<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\models\SliderSettings;

/* 
 * Настройка API
 */
?>

<h4 class="title">
    Настройка API
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php
        $form = ActiveForm::begin([
            'id' => 'form-api-settings',
            'fieldConfig' => [
                'template' => '<div class="field"></i>{label}{input}{error}</div>',
            ],
        ]);
    ?>
    
    <?= $form->field($model, 'api_url')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('api_url'), ['class' => 'field-label']) ?>                

    <div class="save-btn-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>