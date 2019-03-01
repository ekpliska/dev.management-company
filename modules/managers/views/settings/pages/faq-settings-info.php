<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Настройка API
 */
?>

<h4 class="title">
    Часто задаваемые вопросы 
    <?= Html::button('', [
            'class' => 'add-item-settings pull-right',
            'data-target' => '#add-faq-modal-form',
            'data-toggle' => 'modal',
        ]) ?>
</h4>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    <?php if (!empty($faq_settings)) : ?>
        <?php
            $form_faq = ActiveForm::begin([
                'id' => 'multiple-form-faq',
                'fieldConfig' => [
                    'template' => '{label}{input}',
                ],
            ]);
        ?>
        <table class="table table-striped ">
            <thead>
                <tr>
                    <td>Вопрос</td>
                    <td>Ответ</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faq_settings as $index_faq => $faq) : ?> 
                <tr>
                    <td>
                        <?= $form_faq->field($faq, "[$index_faq]faq_question")
                            ->input('text', ['class' => 'settings-input'])->label(false); ?>
                    </td>
                    <td>
                        <?= $form_faq->field($faq, "[$index_faq]faq_answer")
                            ->textarea(['class' => 'settings-input', 'rows' => 6])->label(false); ?>
                    </td>
                    <td>
                        <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'class' => 'delete-item-settings delete-partner-settings',
                                'data' => [
                                    'record' => $faq->id,
                                    'type' => 'faq',
                                ]
                            ]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>