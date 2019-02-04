<?php

    use yii\helpers\Html;
    use app\models\RequestAnswers;

/* 
 * Просмотр отзыва по заявке
 */
?>

<?php if (isset($grade_info) && !empty($grade_info)) : ?>
<table class="table grade-table">
    <tbody>
        <?php foreach ($grade_info as $key => $grade_info) : ?>
        <tr>
            <td><?= $grade_info['question_text'] ?></td>
            <td><?= RequestAnswers::getAnswerText($grade_info['answers']['answer_value']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal-footer">
    <?= Html::button('Закрыть', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
</div>

<?php else: ?>
#TODO
<?php endif; ?>