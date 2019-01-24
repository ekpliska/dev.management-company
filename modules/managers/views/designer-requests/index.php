<?php

    use yii\widgets\Breadcrumbs;
    use yii\bootstrap\Modal;

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
        <?= $this->render("data/index-{$section}", ['section' => $section, 'results' => $results]) ?>
    </div>
    
</div>

<?= $this->render('modal/create-category', ['model_category' => $model_category]) ?>
<?= $this->render('modal/create-service', [
        'model_service' => $model_service, 
        'categories_list' => $results['categories'],
        'units' => $results['units'],
    ]) ?>
