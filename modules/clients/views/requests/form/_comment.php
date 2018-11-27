<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;
/* 
 * Форма добавления комментариев
 */
?>

<?php Pjax::begin(['id' => 'comments']) ?>
    <div class="comments">
        <?php if (isset($comments_find)) : ?>
            <?php foreach ($comments_find as $key => $comment) : ?>
                <div class="chat-badge-date">
                    <span>
                        <?= FormatHelpers::formatDate($comment->created_at, false, 1, false) ?>
                    </span>
                </div>
                <div class="row">
                    <div class="col-sm-1 chat_photo">
                        <?= Html::img($comment['user']->photo, ['class' => 'request-chat-icon']) ?>
                    </div>
                    <div class="col-sm-8 chat-txt-block">
                        <p class="chat-name">
                            <?= $comment['user']['client']->clients_name ? $comment['user']['client']->clients_name : $comment['user']['employer']->employers_name ?>
                            <?= $current_date ?>
                        </p>
                        <?= $comment->comments_text ?>
                    </div>
                    <div class="col-sm-2 chat_time">
                        <?= FormatHelpers::formatDate($comment->created_at, true, 0, true) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
<?php Pjax::end() ?>  

<?php Pjax::begin(['id' => 'new_note']) ?>
    <div class="chat-msg">

        <?php
            $form = ActiveForm::begin([
                'id' => 'add-comment',
                'validateOnSubmit' => true,
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'fieldConfig' => [
                    'template' => '{label}{input}',
                ],
                'options' => [
                    'data-pjax' => true,
                ],
            ]);
        ?>
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'comments_text')
                ->textarea([
                    'placeHolder' => $model->getAttributeLabel('comments_text'), 
                    'rows' => 7])
                ->label(false) ?>    

        <?= Html::submitButton('Отправить <i class="glyphicon glyphicon-arrow-right"></i>', ['class' => 'chat-btn']) ?>

        <?php ActiveForm::end(); ?>

    </div>
<?php Pjax::end() ?>  

<?php
    $this->registerJs('
        $("document").ready(function(){
            $("#new_note").on("pjax:end", function() {
                $.pjax.reload({container:"#comments"});
            });
        });
    ');
?>