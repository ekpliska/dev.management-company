<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;

/* 
 * Рендер списка вопросов для оспроса по завершенной заявки
 */
?>
<?php if (isset($results) && count($results) > 0) :?>
<table class="table table-characteristics table-striped ">
    <tbody>
    <?php foreach ($results as $question) : ?>
        <tr>
            <td class="padding-add"><?= $question['question_text'] ?></td>
            <td class="padding-add">
                <div class="dropdown">
                    <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-option-horizontal"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-setting">
                        <li>
                            <?= Html::a('Редактировать', ['edit-question', 'question_id' => $question['question_id']], ['class' => 'edit-question-btn']) ?>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="question__delete" data-record-type="question" data-record="<?= $question['question_id'] ?>">Удалить</a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>        
</table>
<?php else: ?>
<div class="notice info">
    <p>Вопросы не найдены</p>
</div>
<?php endif; ?>