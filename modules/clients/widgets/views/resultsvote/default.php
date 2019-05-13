<?php

    use yii\helpers\ArrayHelper;

/*
 * Рендер вида результаты голосования
 */
?>
<?php if (!empty($results) && count($results) > 0) : ?>
<div class="questions-text-show-form">
    <table class="table table-voting-results">
        <tbody>
            <?php foreach ($results as $result) : ?>
            <tr>
                <td colspan="3" class="table-voting-results__title">
                    <h4>
                        <i class="glyphicon glyphicon-ok marker-vote-10"></i>
                        <?= $result['text_question'] ?>
                    </h4>
                </td>
            </tr>
            <tr>
                <?php foreach ($result['answers'] as $key => $answer) : ?>
                    <td>
                        <p class="title">
                            <?= ArrayHelper::getValue($type_answers, $key) ?>
                        </p>
                        <p class="results"><?= $answer ?>%</p>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>