<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

?>

<?php
    $form = ActiveForm::begin([
        'id' => 'add-grade-modal-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
    ]);
?>

<table class="table grade-table">
    <tbody>
        <?php foreach ($questions as $key => $question) : ?>
        <tr>
            <td>
                <?= $question['question_text'] ?>
            </td>
            <td>
                <?php
                /*
                 * 'data-answer' => 2 (Да), 'data-answer' => 1 (Нет)
                 */
                ?>
                <div class="btn-group btn-group-lg" role="group" aria-label="Button block" id="btn-group-<?= $key ?>">
                    <?= Html::button($type_answer['2'], [
                                'class' => "btn-grade btn-set-answer btn-yes",
                                'id' => "btn-grade-yes-{$key}",
                                'data-request' => $request,
                                'data-question' => $question['question_id'],
                                'data-answer' => 2,
                        ]) ?>
                    <?= Html::button($type_answer['1'], [
                                'class' => "btn-grade btn-set-answer btn-no",
                                'id' => "btn-grade-no-{$key}",
                                'data-request' => $request,
                                'data-question' => $question['question_id'],
                                'data-answer' => 1,
                        ]) ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2">
                <div id="error-message"></div>
            </td>
        </tr>
    </tbody>
</table>

<div class="modal-footer">
    <?= Html::button('Оценить', [
            'class' => 'btn-modal btn-modal-yes', 
            'id' => 'finished-set-grade',
            'data-request' => $request,
            'data-question' => count($questions),
    ]) ?>
    
    <?= Html::button('Отмена', [
            'class' => 'btn-modal btn-modal-no grade-modal__close', 
            'data-request' => $request,
            'data-dismiss' => 'modal']) ?>
    
</div>

<?php ActiveForm::end(); ?>