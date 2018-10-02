<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;

// Тип публикации, для блокировки чекбоксов смс, емайл, пуш уведомления    
$status_checkbox = $model->isPrivateOffice ? false : true;
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
            <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->news_title, 'width' => 150]) ?>
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
        
        <?= $form->field($model, 'news_text')->widget(Widget::className(), [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'imageUpload' => Url::to(['/managers/news/image-upload']),
                    'imageDelete' => Url::to(['/managers/news/file-delete']),
                    'plugins' => [
                        'fullscreen',
                        'imagemanager',
                        'fontcolor',
                        'table',
                        'fontsize',
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
        <?= $form->field($model, 'isPrivateOffice')
                ->radioList($notice, [
                    'id' => 'type_notice'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'isSMS')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
        
        <?= $form->field($model, 'isEmail')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
        
        <?= $form->field($model, 'isPush')
                ->checkbox([
                    'id' => 'is_notice',
                    'disabled' => $status_checkbox])
                ->label(false) ?>
    </div>
    
    <div class="clearfix"></div>
    
    
    <div class="col-md-12">
        Назначить пользователя (?)
    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
     
    
    <?php ActiveForm::end(); ?>
    