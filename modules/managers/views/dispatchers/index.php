<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Диспетчеры
 */

$this->title = 'Диспетчеры';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= Html::a('Диспетчер (+)', ['dispatchers/add-dispatcher'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
        
        <?= $form->field($search_model, '_input')->input('text')->label() ?>
    
    <?php ActiveForm::end(); ?>
    
    <hr />
    <?= $this->render('data/grid', ['dispatchers' => $dispatchers]) ?>
</div>