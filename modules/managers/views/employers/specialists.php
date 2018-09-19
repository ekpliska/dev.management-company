<?php

    use app\modules\managers\widgets\AlertsShow;
    use yii\helpers\Html;

/* 
 * Специалисты
 */

$this->title = 'Специалисты';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Специалисты (+)', ['employers/add-specialist'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <?php /*
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
        
        <?= $form->field($search_model, '_input')
                ->input('text', [
                    'placeHolder' => 'Поиск...',
                    'id' => '_search-dispatcher',])
                ->label() ?>
    
    <?php ActiveForm::end(); */ ?>
    
    <hr />
    <?php //= $this->render('data/grid_dispatchers', ['dispatchers' => $dispatchers]) ?>
    
    
</div>