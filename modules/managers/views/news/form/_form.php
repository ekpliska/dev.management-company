<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;

/* 
 * Создание новой новости
 */
?>

<div class="news-form row">
<?php
    $form = ActiveForm::begin([
        'id' => 'news-form',
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]);
?>
    <div class="col-md-4 news-form__left-block">
        <div class="load_preview">
            <div class="text-center">
                <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->title]) ?>
            </div>
            <div class="upload-btn-wrapper">
                <?= $form->field($model, 'preview', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
            </div>
        </div>
        <div class="for-whom-block">
            
        <?= $form->field($model, 'status')
                ->radioList($status_publish, ['id' => 'for_whom_news',
                    'item' => function($index, $label, $name, $checked, $value) {
                        $_checked = $checked == 1 ? 'checked' : '';
                        $return = '<label class="input-radio">' . ucwords($label);
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" id="type-vote_' . $index . '"' . $_checked . '>';
                        $return .= '<span class="checkmark"></span>';
                        $return .= '</label>';
                        return $return;
                    }
                ])
                ->label(false); ?>
            
        <?= $form->field($model, 'house', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                ->dropDownList($houses, [
                    'id' => 'adress_list',
                    'class' => 'field-input-select',
                    'prompt' => '[Адрес]'])
                ->label($model->getAttributeLabel('house'), ['class' => 'field-input-select_label']) ?>
            
            
        </div>
        <div class="check-partners-block">
                        
            <?= $form->field($model, 'isAdvert', ['template' => '<div class="el-checkbox">{input}{label}</div>'])
                    ->checkbox(['id' => 'check_advet'], false)
                    ->label($model->getAttributeLabel('isAdvert'), ['class' => 'el-checkbox-style']) ?>
            
            <?= $form->field($model, 'partner', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                    ->dropDownList($parnters, [
                        'id' => 'parnters_list',
                        'prompt' => '[Котрагент]',
                        'class' => 'field-input-select',
                        'disabled' => true,])
                    ->label($model->getAttributeLabel('partner'), ['class' => 'field-input-select_label']) ?>
        </div>
    </div>
    <div class="col-md-8 news-form__right-block">
        
        <?= $form->field($model, 'rubric', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                ->dropDownList($rubrics, ['class' => 'field-input-select', 'prompt' => '[Рубрика]'])
                ->label($model->getAttributeLabel('rubric'), ['class' => 'field-input-select_label']) ?>
        
        <?= $form->field($model, 'title', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                ->textInput(['class' => 'field-input'])
                ->label($model->getAttributeLabel('title'), ['class' => 'field-label'])?>
        
        <?= $form->field($model, 'text')
                ->widget(Widget::className(), [
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 400,
                        'imageUpload' => Url::to(['/managers/news/image-upload']),
                        'imageDelete' => Url::to(['/managers/news/file-delete']),
                        'plugins' => [
                            'fullscreen',
                            'imagemanager',
                            'fontcolor',
                            'table',
                            'fontsize',
                        ]
                    ]])
                ->label(false) ?>

        <?= $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>
        
        <?= $form->field($model, 'isNotice')
                ->checkboxList($notice, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<label class="input-checklist">' . ucwords($label);
                        $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" id="type-notice_' . $index . '"' . '>';
                        $return .= '<span class="checkmark-list"></span>';
                        $return .= '</label>';
                        return $return;
                    }
                ])
                ->label(false); ?>
        
        <div class="news-form__btn-block text-center">
            <?= Html::submitButton('Опубликовать', ['class' => 'btn btn blue-btn']) ?>
        </div>
        
    </div>
    
<?php ActiveForm::end(); ?>    
</div>

<?php /*
    <?php
        $form = ActiveForm::begin([
            'id' => 'news-form',
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    
    <div class="col-md-6">
        
        <?= $form->field($model, 'status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
    </div>

    <div class="col-md-6" style="background: #ffcce6; padding: 10px;">

        <?= $form->field($model, 'isAdvert')
                ->checkbox([
                    'id' => 'check_advet'])
                ->label(false) ?>
        
        <?= $form->field($model, 'partner')
                ->dropDownList($parnters, [
                    'id' => 'parnters_list',
                    'prompt' => 'Выбрать из списка...',
                    'disabled' => true,])
                ->label() ?>
        
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
                        'fontcolor',
                        'table',
                        'fontsize',
                    ],
                ],
            ]) ?>
        
    </div>
    
    <div class="col-md-12">
        <br />
        <?= $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isPrivateOffice')
                ->radioList($notice, [
                    'id' => 'type_notice'])
                ->label(false) ?>

    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isNotice')->checkboxList($notice, [
            'item' => function ($index, $label, $name, $checked, $value) {
                return Html::checkbox($name, $checked, [
                    'value' => $value,
                    'id' => 'is_notice_' . $index,
                    'disabled' => 'disabled'
                ]) . $label;
            }
        ])->label(false) ?>

    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
     
    
    <?php ActiveForm::end(); ?>
    