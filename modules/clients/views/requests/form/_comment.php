<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;
/* 
 * Форма добавления комментариев
 */
$prev_date = 0;    
?>

<?php Pjax::begin(['id' => 'comments']) ?>
    <div class="comments">
        <?php if (isset($comments_find)) : ?>
            <?php foreach ($comments_find as $key => $comment) : ?>
            <?php $prev_date; ?>
                <div class="chat-badge-date">
                    <?= FormatHelpers::formatDateChat($prev_date, $comment->created_at)  ?>
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 chat_photo">
                        <?= Html::img($comment['user']->photo, ['class' => 'request-chat-icon']) ?>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 chat-txt-block">
                        <p class="chat-name">
                            <?= $comment['user']['client']->clients_name ? $comment['user']['client']->clients_name : $comment['user']['employee']->employee_name ?>
                        </p>
                        <?= $comment->comments_text ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-1 col-xs-2 chat_time">
                        <?= FormatHelpers::formatDate($comment->created_at, true, 0, true) ?>
                    </div>
                </div>
        
                <?php $prev_date = $comment->created_at; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
<?php Pjax::end() ?>  

<?php Pjax::begin(['id' => 'new_note']) ?>
    <div class="chat-msg text-right">

        <?php
            $form = ActiveForm::begin([
                'id' => 'add-comment',
                'validateOnSubmit' => true,
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'fieldConfig' => [
                    'template' => '{input}',
                ],
                'options' => [
                    'data-pjax' => true,
                ],
            ]);
        ?>
        <?= $form->field($model, 'comments_text', [
                'template' => '<span id="label-count"></span><span id="label-count-left"></span>{input}'])
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