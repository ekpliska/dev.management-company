<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Справочники
 * Партнеры
 */
?>

<h4 class="title">
    Партнеры 
    <?= Html::button('', [
            'class' => 'add-item-settings pull-right',
            'data-target' => '#add-partner-modal-form',
            'data-toggle' => 'modal',
        ]) ?>
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <?php
        $form_partner = ActiveForm::begin([
            'id' => 'multiple-form-departments',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    <table class="table table-striped ">
        <thead>
            <tr>
                <td>Организация</td>
                <td>Адрес</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($partners as $index_partner => $partner) : ?> 
            <tr>
                <td>
                    <?= $form_partner->field($partner, "[$index_partner]partners_name")
                        ->input('text', ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= $form_partner->field($partner, "[$index_partner]partners_adress")
                        ->input('text', ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                            'class' => 'delete-item-settings delete-partner-settings',
                            'data' => [
                                'record' => $partner->partners_id,
                                'type' => 'partner',
                            ]
                        ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="save-btn-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>