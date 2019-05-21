<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * СМС оповещения
 */
?>

<h4 class="title">
    СМС оповещения
    <p class="balancy-sms pull-right">
        Баланс: 
        <span>
            <?php
                $balancy = Yii::$app->sms->get_balance() ? Yii::$app->sms->get_balance() : 0;
                echo "{$balancy} &#8381;";
            ?>
        </span>
    </p>
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php
        $form = ActiveForm::begin([
            'id' => 'form-sms-settings',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    
    <table class="table table-striped ">
        <tbody>
            <?php foreach ($sms_notices as $index_notice => $notice) : ?> 
            <tr>
                <td>
                    <p class="type-sms-notice">Тип оповещения: <?= $notice->getTypeName($notice->sms_code) ?></p>
                    <?= $form->field($notice, "[$index_notice]sms_text")
                            ->textarea(['class' => 'settings-input', 'rows' => 4])->label(false); ?>
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