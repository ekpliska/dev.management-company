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
    <div class="col-lg-4 col-md-4 col-sm-12 col-md-12 news-form__left-block">
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
    <div class="col-lg-8 col-md-8 col-sm-12 col-md-12 news-form__right-block">
        
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

        <?= $form->field($model, 'files[]', [
                    'template' => '<div class="file-upload"><button class="file-upload__btn"></button>{input}{label}</div>{error}'])
                ->input('file', ['multiple' => true, 'class' => 'file-upload__input'])
                ->label('Добавить вложения', ['class' => 'file-upload__label'])
        ?>
        
        
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
            <?= Html::submitButton('Опубликовать', ['class' => 'btn blue-btn']) ?>
        </div>
        
    </div>
    
<?php ActiveForm::end(); ?>    
</div>