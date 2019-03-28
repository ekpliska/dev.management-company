<?php

    use yii\helpers\Html;
    
?>
<td class="text-question">
    <?= $form->field($question, 'questions_text', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->textInput([
                'id' => "Questions_{$key}_questions_text",
                'name' => "Questions[$key][questions_text]",
                'class' => 'field-input',
                'maxlength' => '250'])
            ->label($question->getAttributeLabel('questions_text'), ['class' => 'field-label']) ?>
    
</td>
<td class="delete-question">
    <?php if ($status !== 1) : ?>
        <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                'class' => 'voting-remove-question-button btn-delete-question',
                'data-toggle' => 'modal',
                'data-target' => '#delete_question_vote_message',
        ]) ?>
    <?php endif; ?>
</td>