<?php

    use app\models\Questions;
    use yii\helpers\Html;
    use app\modules\managers\widgets\Vote;
    
?>
<td class="text-question">
    <?= $form->field($question, 'questions_text', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->textInput([
                'id' => "Questions_{$key}_questions_text",
                'name' => "Questions[$key][questions_text]",
                'class' => 'field-input'])
            ->label($question->getAttributeLabel('questions_text'), ['class' => 'field-label']) ?>
    
    <?php // = \app\modules\managers\widgets\Vote::widget(['question_id' => $key]) ?>
</td>
<td class="delete-question">
    <?php if ($status !== 1) : ?>
        <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                'class' => 'voting-remove-question-button btn btn-delete-question btn-xs',
                'data-toggle' => 'modal',
                'data-target' => '#delete_question_vote_message',
        ]) ?>
    <?php endif; ?>
</td>