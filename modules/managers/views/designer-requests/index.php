<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Конструктор заявок, главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Конструктор заявок';
$this->params['breadcrumbs'][] = 'Конструктор заявок';
?>
<div class="manager-main-with-sub-designer">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <div id="_list-res">
        <?= $this->render("data/index-{$section}", ['section' => $section]) ?>
    </div>
    
</div>