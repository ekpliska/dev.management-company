<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;
    use kartik\date\DatePicker;

/* 
 * Создание новой новости
 */
?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'news-form',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    
    <div class="col-md-12">
        <?= $form->field($model, 'status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'house')->dropDownList($houses, [
            'prompt' => 'Выбрать из списка...',
            'id' => 'adress_list',
        ]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'rubric')->dropDownList($rubrics, [
            'prompt' => 'Выбрать из списка',]) ?>
    </div>
    
    <div class="col-md-2">
        <div class="text-center">
            <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->title, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'preview')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>    
    
    <div class="col-md-10">
        <?= $form->field($model, 'title')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('title'),])
                ->label() ?>
        
        <?= $form->field($model, 'text')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'imageUpload' => Url::to(['/managers/news/image-upload']),
                    'imageDelete' => Url::to(['/managers/news/file-delete']),
                    'plugins' => [
                        'fullscreen',
                        'imagemanager',
                    ],
                ],
            ]) ?>
        
    </div>
    
    <div class="col-md-12">
        Прикрепленные файлы:
        <br />
        <br />
        <br />
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isPrivateOffice')->radioList($notice)->label(false) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isNotice')->checkboxList($type_notice)->label(false) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'date_publish')
                    ->widget(DatePicker::className(), [
                        'language' => 'ru',
                        'options' => [
                            'value' => date('Y-m-d'),
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) ?>
    </div>
    
    <div class="col-md-6">
        Назначить пользователя (?)
    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
     
    
    <?php ActiveForm::end(); ?>
    