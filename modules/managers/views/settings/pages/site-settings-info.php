<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use vova07\imperavi\Widget;

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
        ]);
    ?>
    
    <?= $form->field($model, 'api_url', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('api_url'), ['class' => 'field-label']) ?>

    <?= $form->field($model, 'url_get_receipts', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('url_get_receipts'), ['class' => 'field-label']) ?>
    
    <p class="__settings-p">
        <?= $model->getAttributeLabel('welcome_text') ?>
    </p>
    <?= $form->field($model, 'welcome_text')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 200,
                'plugins' => [
                    'fullscreen',
                    'fontcolor',
                    'fontsize',
                ],
            ],
        ])->label(false) ?>
    
    <p class="__settings-p">
        <?= $model->getAttributeLabel('user_agreement') ?>
    </p>
    <?= $form->field($model, 'user_agreement')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 500,
                'plugins' => [
                    'fullscreen',
                    'fontcolor',
                    'fontsize',
                ],
            ],
        ])->label(false) ?>
    
    <p class="__settings-p">
        <?= $model->getAttributeLabel('promo_block') ?>
    </p>
    <?= $form->field($model, 'promo_block')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 200,
                'plugins' => [
                    'fullscreen',
                    'fontcolor',
                    'fontsize',
                ],
            ],
        ])->label(false) ?>

    <div class="save-btn-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>