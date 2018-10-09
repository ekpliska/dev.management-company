<?php
    use app\models\Questions;
    use yii\helpers\Html;
    
    
?>
<td>
    <?= $form->field($question, 'questions_text')->textInput([
        'id' => "Questions_{$key}_questions_text",
        'name' => "Questions[$key][questions_text]",
    ])->label(false) ?>
    <br />
    <?= $key ?>
</td>
<td>
    <?= Html::button('Удалить вопрос', [
            'class' => 'voting-remove-question-button btn btn-default btn-xs',
//            'data-toggle' => 'modal',
//            'data-target' => '#delete_question_vote_message',
    ]) ?>
</td>