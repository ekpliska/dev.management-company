<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;
/* 
 * Форма добавления комментариев
 */
?>

<?php Pjax::begin(['id' => 'comments']) ?>
    <div class="panel panel-default">
        <div class="panel-heading">Комментарии</div>
        <div class="panel-body">
            <div class="container-fluid">
                    <?php if (isset($comments_find)) : ?>

                    <?php foreach ($comments_find as $key => $comment) : ?>
                        <div class="row">
                            
                            <?php /* Дифференцируем выводимые сообщения для пользователей, на входящие/исходящие */ ?>
                            
                            <?php if ($comment['user']->id == Yii::$app->user->identity->user_id) : ?>
                            
                                <div class="col-8 col-sm-12">
                                    <strong><?= $comment['user']['client']->clients_name ?></strong>
                                    <?= FormatHelpers::formatDate($comment->created_at) ?>
                                </div>
                                <div class="col-8 col-sm-2">
                                    <?php if ($comment['user']->user_photo) : ?>
                                        <?= Html::img($comment['user']->user_photo, ['style' => 'width: 50px;']) ?>
                                    <?php else: ?>
                                        <?= Html::img('@web/images/no-avatar.jpg', ['style' => 'width: 50px;']) ?>                            
                                    <?php endif; ?>
                                </div>                            
                                <div class="col-8 col-sm-10" style="background: #337ab7; padding: 5px; color: #fff; border-radius: 5px; position: relative; top: 5px;">
                                    <?= $comment->comments_text ?>
                                </div>
                            
                            <?php else: ?>
                            
                                <div class="col-8 col-sm-12 text-right">
                                    <?= FormatHelpers::formatDate($comment->created_at) ?>
                                    <strong>Фамилия И.О (диспетчера)</strong>
                                </div>
                                <div class="col-8 col-sm-10 text-right" style="background: #c1c1c1; padding: 5px; color: #fff; border-radius: 5px; position: relative; top: 5px;">
                                    <?= $comment->comments_text ?>
                                </div>                            
                                <div class="col-8 col-sm-2">
                                    <?php if ($comment['user']->user_photo) : ?>
                                        <?= Html::img($comment['user']->user_photo, ['style' => 'width: 50px;']) ?>
                                    <?php else: ?>
                                        <?= Html::img('@web/images/no-avatar.jpg', ['style' => 'width: 50px;']) ?>                            
                                    <?php endif; ?>
                                </div>                              
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <br />
                <hr />
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'add-comment',                    
                        'options' => [
                            'data-pjax' => true,
                        ]
                    ])
                ?>
                    <?= $form->field($model, 'comments_text')
                            ->textarea([
                                'placeHolder' => $model->getAttributeLabel('comments_text'), 
                                'rows' => 6])
                            ->label(false) ?>
                
                    <div class="form-group text-right"> 
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
                    </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
<?php Pjax::end() ?>

<?php
$this->registerJs(
   '$("document").ready(function(){
        $("#comments").on("pjax:end", function(e) {
            e.preventDefault();
            $.pjax.reload({container:"#comments"});  
	});
    });'
);
?>