<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Создание новой новости
 */

$this->title = 'Новость (+)';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'news-form',
        ]);
    ?>
    
    <div class="col-md-12">
        <?= $form->field($model, 'status')->radioList($status_publish)->label(false) ?>        
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'house')->dropDownList($houses, [
            'prompt' => 'Выбрать из списка...',
        ]) ?>
    </div>
    
    <div class="col-md-6">
        <?= $form->field($model, 'rubric')->dropDownList($rubrics, [
            'prompt' => 'Выбрать из списка',]) ?>
    </div>
    
    <div class="col-md-12">
        <?= $form->field($model, 'title')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('title'),])
                ->label() ?>
        
        <?= $form->field($model, 'text')
                ->input('text', [
                    'placeHolder' => $model->getAttributeLabel('text'),])
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
        <?= $form->field($model, 'isNotice')->checkboxList($type_notice)->label(false) ?>
    </div>
    
    <div class="col-md-12">
        Дата публикации: 11
        Автор: 222
    </div>
    
    <div class="col-md-12 text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
     
    
    <?php ActiveForm::end(); ?>
    
</div>