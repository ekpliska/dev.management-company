<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\models\SliderSettings;

/* 
 * Справочники
 * Партнеры
 */
?>

<h4 class="title">
    Настройки слайдера на главной странице
    <?= Html::button('', [
            'class' => 'add-item-settings pull-right',
            'data-target' => '#add-slider-modal-form',
            'data-toggle' => 'modal',
        ]) ?>
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <?php
        $form_slider = ActiveForm::begin([
            'id' => 'multiple-form-sliders',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    <table class="table table-striped ">
        <thead>
            <tr>
                <td>Статус</td>
                <td>Заголовок</td>
                <td>Комментарий</td>
                <td>Кнопка</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sliders as $index_slider => $slider) : ?> 
            <tr>
                <td>
                    <?= Html::button('<i class="glyphicon glyphicon-ok"></i>', [
                            'id' => "switch-status-{$index_slider}",
                            'class' => ($slider->is_show == SliderSettings::STATUS_SHOW) ? 'switch-status-slider-on ' : 'switch-status-slider-on __slider-off',
                            'data' => [
                                'record' => $slider->slider_id,
                            ]
                        ]) ?>
                </td>
                <td>
                    <?= $form_slider->field($slider, "[$index_slider]slider_title")
                        ->input('text', ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= $form_slider->field($slider, "[$index_slider]slider_text")
                        ->textarea(['class' => 'settings-input', 'rows' => 6])->label(false); ?>
                </td>
                <td>
                    <?= $form_slider->field($slider, "[$index_slider]button_1")
                        ->input('text', ['class' => 'settings-input', 'placeHolder' => 'https://site-name.com'])->label(false); ?>
                    <?= $form_slider->field($slider, "[$index_slider]button_2")
                        ->input('text', ['class' => 'settings-input', 'placeHolder' => 'https://site-name.com'])->label(false); ?>
                </td>
                <td>
                    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                            'class' => 'delete-item-settings delete-partner-settings',
                            'data' => [
                                'record' => $slider->slider_id,
                                'type' => 'slider',
                            ]
                        ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    <?php ActiveForm::end(); ?>
</div>