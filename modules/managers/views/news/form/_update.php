<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;
    use app\helpers\FormatHelpers;

/*
 * Форма редатирования новости
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
                <?= Html::img($model->preview, ['id' => 'photoPreview', 'class' => 'img-rounded', 'alt' => $model->news_title]) ?>
            </div>
            <div class="upload-btn-wrapper">
                <?= $form->field($model, 'news_preview', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden', 'accept' => 'image/*'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
            </div>
        </div>
        
        <div class="for-whom-block">
            <?= $form->field($model, 'news_status')
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
                    ->label(false) ?>
            
            <?= $form->field($model, 'news_house_id', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                    ->dropDownList($houses, [
                        'id' => 'adress_list',
                        'class' => 'field-input-select'])
                    ->label($model->getAttributeLabel('news_house_id'), ['class' => 'field-input-select_label']) ?>
        </div>
        
        <div class="check-partners-block">
            <?= $form->field($model, 'isAdvert', ['template' => '<div class="el-checkbox">{input}{label}</div>'])
                    ->checkbox(['id' => 'check_advet'], false)
                    ->label($model->getAttributeLabel('isAdvert'), ['class' => 'el-checkbox-style']) ?>
            
            <?= $form->field($model, 'news_partner_id', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                    ->dropDownList($parnters, [
                        'id' => 'parnters_list',
                        'prompt' => '[Котрагент]',
                        'class' => 'field-input-select',
                        'disabled' => true,])
                    ->label($model->getAttributeLabel('news_partner_id'), ['class' => 'field-input-select_label']) ?>
        </div>
        
        
    </div>
    
    <div class="col-md-8 news-form__right-block">
        <?= $form->field($model, 'news_type_rubric_id', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                ->dropDownList($rubrics, ['class' => 'field-input-select', 'prompt' => '[Рубрика]'])
                ->label($model->getAttributeLabel('news_type_rubric_id'), ['class' => 'field-input-select_label']) ?>
        
        <?= $form->field($model, 'news_title', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                ->textInput(['class' => 'field-input'])
                ->label($model->getAttributeLabel('news_title'), ['class' => 'field-label'])?>
        
        <?= $form->field($model, 'news_text')
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
        
            <table class="table table-upload-file">
                <thead>
                    <tr>
                        <th>
                            <?= $form->field($model, 'files[]', [
                                        'template' => '<div class="file-upload"><button class="file-upload__btn"></button>{input}{label}</div>{error}'])
                                    ->input('file', ['multiple' => true, 'class' => 'file-upload__input'])
                                    ->label('Добавить вложения', ['class' => 'file-upload__label'])
                            ?>
                        </th>
                    </tr>    
                </thead>
                
                <?php if (isset($docs) && count($docs) > 0) : ?>
                        <tbody>
                        <?php foreach ($docs as $doc) : ?>
                            <tr>
                                <td>
                                    <?= FormatHelpers::formatUrlByDoc($doc['name'], $doc['filePath']) ?>
                                    <?= Html::beginTag('span', ['class' => 'delete_span', 'data-files' => $doc['id']]) ?>
                                        <?= '&times;' ?>
                                    <?= Html::endTag('span') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                <?php endif; ?>
            </table>

        <div class="news-form__btn-block text-center">
            
            <?= Html::button('Удалить', [
                    'class' => 'btn orange-btn',
                    'data-target' => '#delete_news_manager',
                    'data-toggle' => 'modal',
                    'data-news' => $model->news_id]) ?>
            
            <?= Html::submitButton('Сохранить', ['class' => 'btn blue-btn']) ?>
        </div>
        
    </div>

<?php ActiveForm::end(); ?>    
</div>