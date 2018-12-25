<?php

    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
    use app\models\TypeCounters;

/* 
 * Рендер вида Приборы учета
 */

// Текущая дата    
$current_date = date('Y-m-d');

// Массив изображений приборов учета, по ID типа прибора учета
$array_image = [
    '1' => 'hold-water',
    '2' => 'hot-water',
    '3' => 'electric-meter',
    '4' => 'heating-meter',
    '5' => 'heat-distributor',
];
?>

<?php if (isset($counters_lists) && is_array($counters_lists)) : // 1 ?>

<?php 
    // Если переключатель возможности ввода показаний включен, то формируем форму для ввода показаний
    if ($can_set_indication == true) :
        $model_indication = new app\modules\managers\models\form\CounterIndicationsForm();
        $model_notice = new app\models\CommentsToCounters();
        $form = ActiveForm::begin([
            'id' => 'send-counters-form',
        ]);        
    endif;
?>

<?php 
    foreach ($counters_lists as $key => $indication) : 
    // Переключатель необходимлсти поверки прибора учета
    $need_check = (strtotime($current_date) >= strtotime($indication['Дата следующей поверки'])) ? true : false;
?>    
    <tr class="<?= ($need_check == true) ? 'block-edit-reading' : '' ?>">
        <td class="image-counters">
            <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indication['Тип прибора учета'])] . '.svg', ['alt' => '']) ?>
        </td>
        <td>
            <p class="counter-name"><?= $indication['Тип прибора учета'] ?></p>
            <p class="counter-number"><?= $indication['Регистрационный номер прибора учета'] ?></p>
        </td>
        <td><?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?></td>
        <td><?= Yii::$app->formatter->asDate($indication['Дата снятия показания'], 'long') ?></td>
        <td><?= $indication['Предыдущие показание'] ?></td>
        <td>
            <?php if ($need_check == true) : // Если прибор учета является проблемным (истек срок покерки счетчика) ?>
                <?= Html::button('Заказать поверку', [
                        'class' => 'create-send-request', 
                        'data-counter-type' => $indication['Тип прибора учета'],
                        'data-counter-num' => $indication['Регистрационный номер прибора учета'],
                ]) ?>
            <?php else: ?>
                <?= $form->field($model_indication, "[{$indication['ID']}]counter_id_client")
                        ->hiddenInput(['class' => 'reading-input', 'value' => $indication['ID']])
                        ->label(false) ?>
                <?= $form->field($model_indication, "[{$indication['ID']}]current_indication")
                        ->input('text', ['class' => 'reading-input'])
                        ->label(false) ?>
            <?php endif; ?>
        </td>
        <td></td>
    </tr>

<?php endforeach; ?>

<?php if ($can_set_indication == true) : ?>
    
    <tr class="form-notice-in-table">
        <td colspan="7">
            <?= $form->field($model_notice, 'comments_title', [
                    'template' => '<div class="field has-label"></i>{label}{input}{error}</div>'])
                    ->input('text', ['class' => 'field-input'])
                    ->label($model_notice->getAttributeLabel('comments_title'), ['class' => 'field-label']) ?>
            
            <?= $form->field($model_notice, 'comments_text', [
                    'template' => '<div class="field-page-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                    ->textarea(['rows' => 10, 'class' => 'field-input-textarea-page comment'])
                    ->label($model_notice->getAttributeLabel('comments_text'), ['class' => 'field-label']) ?>
        </td>
    </tr>
    
    <tr class="btn-block-in-table">
        <td colspan="7">
            <?= Html::submitButton('Сохранить', ['id' => 'send-current-indication', 'class' => 'btn blue-btn']) ?>
        </td>
    </tr>
    
<?php 
    
    ActiveForm::end();
    endif; 
?>
    

<?php endif; ?>

<?php /*
    <?php foreach ($counters_lists as $key => $indication) : ?>
    <?php $data_check = (strtotime($current_date) >= strtotime($indication['Дата следующей поверки'])) ? true : false ?>
    <tr class="<?= ($data_check == true) ? 'block-edit-reading' : '' ?>">
        <td class="image-counters">
            <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indication['Тип прибора учета'])] . '.svg', ['alt' => '']) ?>
        </td>
        <td>
            <p class="counter-name"><?= $indication['Тип прибора учета'] ?></p>
            <p class="counter-number"><?= $indication['Регистрационный номер прибора учета'] ?></p>
        </td>
        <td><?= Yii::$app->formatter->asDate($indication['Дата следующей поверки'], 'long') ?></td>
        <td><?= Yii::$app->formatter->asDate($indication['Дата снятия показания'], 'long') ?></td>
        <td><?= $indication['Предыдущие показание'] ?></td>

        <?php if ($data_check == true) : // Если прибор учета является проблемным, то доступ к вводу показаний блокируем if 2 ?>
        <td><span>Ввод показаний заблокирован</span></td>
        <td>
            <?= Html::button('Заказать поверку', [
                    'class' => 'create-send-request', 
                    'data-counter-type' => $indication['Тип прибора учета'],
                    'data-counter-num' => $indication['Регистрационный номер прибора учета'],
            ]) ?>
        </td>
        <?php else: // Если прибор учета не является проблемным ?>
        <td>
            <?php if ($indication['Текущее показание'] != null) : // 4 ?>
                <?= $indication['Текущее показание'] ?>
            <?php else: // else if 4 ?>
                <?php Html::beginForm() ?>
                <?= Html::hiddenInput("[{$indication['ID']}]counter_id", $indication['ID'], ['class' => 'reading-input']) ?>
                <?= Html::input('text', "[{$indication['ID']}]previous_val", $indication['Предыдущие показание'], ['class' => 'reading-input']) ?>
                <?= Html::input('text', "[{$indication['ID']}]current_val", null, ['class' => 'reading-input']) ?>
                <?= Html::endForm(); ?>
            <?php endif; // end if 4 ?>
        </td>                
        <td>
            <?= $indication['Текущее показание'] ? $indication['Текущее показание'] - $indication['Предыдущие показание'] : '' ?>
        </td>
        <?php endif; // end if 2 ?>
    </tr>
    <?php endforeach; ?>

<?php
    if ($need_verification === true) : // if 5 Форма комментрария к приборам учета выводится, если существует проблемный прибор учета
        
    $form = ActiveForm::begin([
        'id' => 'send-counters-form',
        'fieldConfig' => [
            'template' => '<div class="field has-label"></i>{label}{input}{error}</div>',
        ],        
    ]);
?>
    
    <tr class="form-notice-in-table">
        <td colspan="7">
            <?= $form->field($model_notice, 'comments_title')
                    ->input('text', ['class' => 'field-input'])
                    ->label($model_notice->getAttributeLabel('comments_title'), ['class' => 'field-label']) ?>
            
            <?= $form->field($model_notice, 'comments_text', [
                    'template' => '<div class="field-page-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
                    ->textarea(['rows' => 10, 'class' => 'field-input-textarea-page comment'])
                    ->label($model_notice->getAttributeLabel('comments_text'), ['class' => 'field-label']) ?>
            
        </td>
    </tr>
    

    <tr class="btn-block-in-table">
        <td colspan="7">
            <?= Html::submitButton('Сохранить', ['id' => 'send-current-indication', 'class' => 'btn blue-btn']) ?>
        </td>
    </tr>
    
<?php ActiveForm::end(); ?>  
<?php endif; // end if 5 ?>
    
<?php else: // else if 1 ?>
    <tr class="status-not-found">
        <td colspan="7">
            Показания приборов учета для текущего лицевого счета не найдены.
        </td>
    </tr>                
<?php endif; // else if 1 ?>
  */ ?>