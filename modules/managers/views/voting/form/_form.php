<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\datetime\DateTimePicker;

/* 
 * Форма создать голосование
 */

?>
<?php
    $form = ActiveForm::begin([
        'id' => 'create-voting',
        'options' => [
                'enctype' => 'multipart/form-data',
        ],
    ]);
?>

    <div class="col-md-6">
        
        <?= $form->field($model, 'type')
                ->radioList($type_voting, [
                    'id' => 'type_voting',])
                ->label(false) ?>
        
        <?= $form->field($model, 'object_vote')->dropDownList($object_vote, [
                'prompt' => 'Выбрать из списка...',
                'id' => 'object_vote_list',
        ]) ?>
        
    </div>

    <div class="col-md-6">
        
        <div class="text-center">
            <?= Html::img($model->image, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->title, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'image')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
        
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'title')
            ->input('text', [
                'placeHolder' => $model->getAttributeLabel('title')])
            ->label() ?>
    </div>
    
    <div class="col-md-12">
        <?= $form->field($model, 'text')
            ->input('text', [
                'placeHolder' => $model->getAttributeLabel('text')])
            ->label() ?>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            
            <?= $form->field($model, 'date_start')
                    ->widget(DateTimePicker::className(), [
                        'id' => 'date_voting_start',
                        'language' => 'ru',
                        'options' => [
                            'placeholder' => 'Дата начала голосования',
                        ],
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'dd.mm.yyyy hh:ii',
                        ]
                    ]) ?>
            
            <?= $form->field($model, 'date_end')
                    ->widget(DateTimePicker::className(), [
                        'id' => 'date_voting_end',
                        'language' => 'ru',
                        'options' => [
                            'placeholder' => 'Дата завершения голосования',
                        ],
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'dd.mm.yyyy hh:ii',
                        ]
                    ]) ?>            
            
        </div>
        <div class="col-md-8" style="border: 1px solid #b3ecff; padding: 10px;">
            <h4>Вопросы</h4>
            <hr />
            <?= Html::button('Добавить', ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <div class="col-md-12 text-center">
        <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-primary']) ?>
    </div>


<?php ActiveForm::end() ?>