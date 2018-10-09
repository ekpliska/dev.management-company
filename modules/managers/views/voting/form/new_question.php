<?php
    use app\models\Questions;
    use yii\helpers\Html;
    
    
?>
<td>
    <?= $form->field($question, 'questions_text')->textInput([
        'id' => "Questions_{$key}_questions_text",
        'name' => "Questions[$key][questions_text]",
    ])->label(false) ?>
</td>
<td>
    <?= Html::a('Удалить вопрос', 'javascript:void(0);', [
      'class' => 'voting-remove-question-button btn btn-default btn-xs',
    ]) ?>
</td>