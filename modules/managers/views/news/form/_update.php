<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use vova07\imperavi\Widget;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\ModalWindowsManager;    

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
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;Загрузить фото') ?>
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
                    ->label($model->getAttributeLabel('partner'), ['class' => 'field-input-select_label']) ?>
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
        
            <?php // = $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>

            <table class="table table-upload-file">
                <thead>
                    <tr>
                        <th>
                            <?= $form->field($model, 'files[]', ['template' => '<label class="text-center btn-upload-cover" role="button">{input}{label}{error}</label>'])
                                    ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('<i class="glyphicon glyphicon-download-alt"></i> Добавить документ') ?>
                        </th>
                    </tr>    
                </thead>
                
                <?php if (isset($docs) && count($docs) > 0) : ?>
                        <tbody>
                        <?php foreach ($docs as $doc) : ?>
                            <tr>
                                <td>
                                    <?= FormatHelpers::formatUrlByDoc($doc['name'], $doc['filePath']) ?>
                                    <?= Html::button('&#10005;', [
                                            'class' => 'delete_file',
                                            'data-files' => $doc['id']]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                <?php endif; ?>
            </table>

            <div class="spam-agree-txt notice-block">
                <?= $form->field($model, 'isPush', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 
                <?= $form->field($model, 'isPrivateOffice', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 
                <?= $form->field($model, 'isEmail', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 
            </div>            

            
        <div class="news-form__btn-block text-center">
            
            <?= Html::button('Удалить', [
                    'class' => 'btn delete-record-btn',
                    'data-target' => '#delete_news_manager',
                    'data-toggle' => 'modal',
                    'data-news' => $model->news_id,
                    'data-is-advert' => $model->isAdvert]) ?>            
            
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn blue-btn']) ?>
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
        <?= $form->field($model, 'news_status')
                ->radioList($status_publish, [
                    'id' => 'for_whom_news'])
                ->label(false) ?>
        
    </div>

    <div class="col-md-6" style="background: #ffcce6; padding: 10px;">

        <?= $form->field($model, 'isAdvert')
                ->checkbox([
                    'id' => 'check_advet'])
                ->label(false) ?>
        
        <?= $form->field($model, 'news_partner_id')
                ->dropDownList($parnters, [
                    'id' => 'parnters_list',
                    'prompt' => 'Выбрать из списка...',
                    'disabled' => $model->isAdvert ? false : true,])
                ->label() ?>
        
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
        <?= $form->field($model, 'files[]')->input('file', ['multiple' => true])->label() ?>
        <hr />
        <?php if (isset($docs) && count($docs) > 0) : ?>
            <?php foreach ($docs as $doc) : ?>
                <?= FormatHelpers::formatUrlByDoc($doc['name'], $doc['filePath']) ?>
                <?= Html::button('Удалить', [
                        'class' => 'btn btn-link btn-sm delete_file',
                        'data-files' => $doc['id'],]) ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr />
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'isPrivateOffice')
                ->radioList($notice, [
                    'id' => 'type_notice'])
                ->label(false) ?>
    </div>
    
    <div class="col-md-6">
        <?php /*
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
                ->label(false)  ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="col-md-12 text-right">
        
        <?= Html::button('Удалить', [
            'class' => 'btn btn-danger',
            'data-target' => '#delete_news_manager',
            'data-toggle' => 'modal',
            'data-news' => $model->news_id,
            'data-is-advert' => $model->isAdvert]) ?>
        
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        
    </div>
     
    
    <?php ActiveForm::end(); ?>
*/ ?>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_news']) ?>