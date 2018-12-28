<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Breadcrumbs;
    use vova07\imperavi\Widget;
    use kartik\datetime\DateTimePicker;
    use app\modules\managers\widgets\AlertsShow;
    use app\models\Questions;

/* 
 * Голосование, создание голосования
 */

$this->title = 'Голосование';
$this->title = Yii::$app->params['site-name-manager'] . 'Голосование';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = 'Новая запись [Голосование]';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
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
            <?= $form->field($model, 'imageFile', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
            </div>
            
        </div>
        <div class="row form-vote">
            <div class="col-md-6">
                <?= $form->field($model->voting, 'voting_date_start', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                        ->widget(DateTimePicker::className(), [
                            'id' => 'date_voting_start',
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                            ],
                            'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd hh:ii',
                            ]])
                    ->label($model->getAttributeLabel('voting_date_start'), ['class' => 'date-label']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model->voting, 'voting_date_end', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                        ->widget(DateTimePicker::className(), [
                            'id' => 'date_voting_end',
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                            ],
                            'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd hh:ii',
                            ]])
                        ->label($model->getAttributeLabel('voting_date_start'), ['class' => 'date-label']) ?> 
            </div>
            <div class="col-md-12">
                <?= $form->field($model->voting, 'voting_title', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                        ->textInput(['class' => 'field-input'])
                        ->label($model->voting->getAttributeLabel('voting_title'), ['class' => 'field-label'])?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model->voting, 'voting_text')->widget(Widget::className(), [
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 200,
                        'plugins' => [
                            'fullscreen',
                            'fontcolor',
                            'table',
                            'fontsize',
                        ],
                    ],
                ])->label(false) ?>
            </div>            
        </div>
        <div class="row form-vote-questions">
            <div class="col-md-4 housing-lists">
                <?=
                    $form->field($model->voting, 'voting_type')
                        ->radioList($type_voting, 
                            [
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $return = '<label class="input-radio">' . ucwords($label);
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="type-vote_' . $index . '">';
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
                            'prompt' => 'Выбрать дом из списка...'])
                        ->label($model->voting->getAttributeLabel('voting_house_id'), ['class' => 'field-input-select_label']) ?>
            </div>
            
            <div class="col-md-8 questions-list">
                <fieldset>
                    <?php $question = new Questions(); ?>
                    <table id="voting-questions" class="table">
                        <tbody>
                            <?php // Формируем поле для ввода вопроса для текущего голосования ?>
                            <?php foreach ($model->questions as $key => $_question) : ?>
                                <tr>
                                <?= $this->render('form/new_question', [
                                        'key' => $_question->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_question->questions_id,
                                        'form' => $form,
                                        'question' => $_question,
                                        'status' => $model->voting->status,
                                    ]) 
                                ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php // Поля для нового вопроса ?>
                            <tr id="voting-new-question-block" style="display: none;">
                                <?= $this->render('form/new_question', [
                                        'key' => '__id__',
                                        'form' => $form,
                                        'question' => $question,
                                        'status' => $model->voting->status,
                                    ])
                                ?>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div id="title-block-of-questions">
                        <?php if ($model->voting->status !== 1) : ?>
                            <?= Html::a('Добавить вопрос', 'javascript:void(0);', [
                                    'id' => 'voting-new-question-button', 
                                    'class' => 'add-question-btn'
                                ])
                            ?>
                        <?php endif; ?>
                    </div>
                    
                    
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
            </div>
        </div>
    </div>
    
    
    <?= Html::submitButton($model->voting->isNewRecord ? 'Опубликовать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
    
    <?php ActiveForm::end(); ?>
    
</div>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
        'model' => $model,
        'type_voting' => $type_voting,
    ]) ?>
    
</div>
 * 
 */ ?>