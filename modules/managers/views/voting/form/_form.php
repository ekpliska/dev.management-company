<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use kartik\datetime\DateTimePicker;
    use vova07\imperavi\Widget;
    use app\models\Questions;
    use app\modules\managers\widgets\Vote;

/*
 * Форма голосования
 */
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>


<div class="create-vote-profile">
    <?php $form = ActiveForm::begin([
        'id' => 'form-voting',
        'enableClientValidation' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
        ]); 
    ?>        
        
        <div class="cover-vote">
            <?= Html::img($model->voting->image, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => 'cover-vote']) ?>
            
            <div class="upload-btn-wrapper">
            <?= $form->field($model->voting, 'imageFile', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                    ->input('file', ['id' => 'btnLoad', 'class' => 'hidden', 'accept' => 'image/*'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
            </div>
            
            <div class="cover-block-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?= $form->field($model->voting, 'voting_date_start', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                            ->widget(DateTimePicker::className(), [
                                'id' => 'date_voting_start',
                                'language' => 'ru',
                                'value' => date('Y-m-d'),
                                'options' => [
                                    'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                                ],
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoClose' => true,
                                    'format' => 'yyyy-mm-dd hh:ii',
                                ]])
                            ->label($model->voting->getAttributeLabel('voting_date_start'), ['class' => 'date-label']) ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?= $form->field($model->voting, 'voting_date_end', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                            ->widget(DateTimePicker::className(), [
                                'id' => 'date_voting_end',
                                'language' => 'ru',
                                'value' => date('yyyy-mm-dd hh:ii'),
                                'options' => [
                                    'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                                ],
                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoClose' => true,
                                    'format' => 'yyyy-mm-dd hh:ii',
                                ]])
                            ->label($model->voting->getAttributeLabel('voting_date_end'), ['class' => 'date-label']) ?> 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= $form->field($model->voting, 'voting_title', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                            ->textInput(['class' => 'field-input'])
                            ->label($model->voting->getAttributeLabel('voting_title'), ['class' => 'field-label'])?>
                </div>                
            </div>
            
        </div>
    
        <div class="row form-vote">
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                
                <?= $form->field($model->voting, 'voting_text', [
                            'template' => '<div class="field-page-textarea">{label}{input}{error}</div>'])
                        ->textarea(['rows' => 10, 'class' => 'field-input-textarea-page'])
                        ->label($model->voting->getAttributeLabel('voting_text'), ['class' => 'field-label']) ?>
                
            </div>            
        </div>
    
        <div class="row form-vote-questions">
            <div class="col-md-4 col-md-4 col-xs-12 col-md-12">
                <div class="housing-lists">
                    <?=
                        $form->field($model->voting, 'voting_type')
                            ->radioList($type_voting, ['id' => 'for_whom_voting',
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        $_checked = $checked == 1 ? 'checked' : '';
                                        $return = '<label class="input-radio">' . ucwords($label);
                                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="type-vote_' . $index . '"' . $_checked . '>';
                                        $return .= '<span class="checkmark"></span>';
                                        $return .= '</label>';

                                        return $return;
                                    }
                                ]
                            )
                        ->label(false);
                        ?>
                    <?= $form->field($model->voting, 'voting_house_id', ['template' => '<div class="field"></i>{label}{input}{error}</div>',])
                            ->dropDownList($houses_array, [
                                'id' => 'house-lists',
                                'class' => 'field-input-select',
                                'prompt' => '[Адрес]'])
                            ->label($model->voting->getAttributeLabel('voting_house_id'), ['class' => 'field-input-select_label']) ?>
                </div>
                
                <?php /* Блок формирования участников, завершивших голосование */ ?>
                <?php if (isset($participants) && count($participants) > 0) : ?>
                <div class="participant-list">
                    <p><span class="span-count"><?= count($participants) ?></span> Проголосовало</p>
                        <?php foreach ($participants as $participant) : ?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 voting__participant_info text-center">
                                <?php $avatar = $participant['user_photo'] ? $participant['user_photo'] : "/images/no-avatar.jpg" ?>
                                <?= Html::img($avatar, ['alt' => 'user-name', 'class' => 'img-responsive img-circle user-photo']) ?>
                                <?= Html::a($participant['clients_name'], ['view-profile', 'user_id' => $participant['user_id']], [
                                        'id' => 'view-profile',
                                ]) ?>
                            </div>
                        <?php endforeach; ?>
                </div>
                <?php endif; ?>                        
            </div>
            
            <div class="col-md-8 col-md-8 col-xs-12 col-md-12 questions-list">
                <fieldset>
                    <?php $question = new Questions(); ?>
                    <table id="voting-questions" class="table">
                        <tbody>
                            <?php // Формируем поле для ввода вопроса для текущего голосования ?>
                            <?php foreach ($model->questions as $key => $_question) : ?>
                            <tr>
                            <?= $this->render('new_question', [
                                    'key' => $_question->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_question->questions_id,
                                    'form' => $form,
                                    'question' => $_question,
                                    'status' => $model->voting->status,
                                ]) 
                            ?>
                            </tr>
                            <?php if (!$model->voting->isNewRecord) : ?>
                            <tr class="voting-questions-result">
                                <?= Vote::widget(['question_id' => $_question->questions_id, 'count' => count($participants)]) ?>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php // Поля для нового вопроса ?>

                            <tr id="voting-new-question-block" style="display: none;">
                                <?= $this->render('new_question', [
                                        'key' => '__id__',
                                        'form' => $form,
                                        'question' => $question,
                                        'status' => $model->voting->status,
                                    ])
                                ?>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                    <?php if ($model->voting->status !== 1) : ?>
                        <div id="title-block-of-questions">
                            <?= Html::a('Добавить вопрос', 'javascript:void(0);', [
                                    'id' => 'voting-new-question-button', 
                                    'class' => 'add-question-btn'
                                ])
                            ?>
                        </div>
                    <?php endif; ?>                    
                    
                    <?php ob_start(); // включаем буферизацию для js ?>

<script>
    // Добавление кнопки нового вопроса
    var question_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
    $('#voting-new-question-button').on('click', function () {
        question_k += 1;
        $('#voting-questions').find('tbody')
            .append('<tr>' + $('#voting-new-question-block').html().replace(/__id__/g, 'new' + question_k) + '</tr>');
        });
            
    /*
     * Запрос на удаление вопроса
     */
    var elemQiestion;
    $(document).on('click', '.voting-remove-question-button', function () {
        elemQiestion = $(this).closest('tbody tr');
    });
    $('.delete_question').on('click', function(){
        elemQiestion.remove();
    });


    <?php
        if (!Yii::$app->request->isPost && $model->voting->isNewRecord) 
            echo "$('#voting-new-question-button').click();";
    ?>
</script>

<?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

                </fieldset>
                
                <div class="vote-btn-block text-center">
                    
                    <?php if ($controller == 'voting' && $action == 'view') : ?>
                    
                        <?= Html::button('Удалить', [
                                'class' => 'btn orange-btn delete-record-btn',
                                'data-toggle' => 'modal',
                                'data-target' => '#delete_voting_manager',
                                'data-voting' => $model->voting->voting_id]) ?>

                        <?php if ($model->voting->status !== 1) : ?>
                            <?= Html::button('Завершить голосование', [
                                    'class' => 'btn blue-btn close_voting_btn',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#close_voting',
                                    'data-voting' => $model->voting->voting_id]) ?>
                        <?php endif; ?>                    

                    <?php endif; ?>
                    
                    <?= Html::submitButton($model->voting->isNewRecord ? 'Опубликовать' : 'Сохранить', ['class' => 'btn white-btn']); ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>