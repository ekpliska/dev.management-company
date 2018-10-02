<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\date\DatePicker;
    
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
        <?= $form->field($model, 'news_status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'news_house_id')->dropDownList($houses, [
            'id' => 'adress_list',
        ]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'news_type_rubric_id')->dropDownList($rubrics) ?>
    </div>
    
    <div class="col-md-2">
        <div class="text-center">
            <?= Html::img($model->news_preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->news_title, 'width' => 150]) ?>
        </div>
        <br />
        <?= $form->field($model, 'news_preview')
                ->input('file', ['id' => 'btnLoad'])
                ->label(false) ?>
    </div>    
    
    <div class="col-md-10">
        <?= $form->field($model, 'news_title')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('news_title'),])
                ->label() ?>
        
        <?= $form->field($model, 'news_text')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('news_text'),])
                ->label() ?>
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
        <?= $form->field($model, 'isSMS')->checkbox()->label(false) ?>
        <?= $form->field($model, 'isEmail')->checkbox()->label(false) ?>
        <?= $form->field($model, 'isPush')->checkbox()->label(false) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'created_at')
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
    
</div>