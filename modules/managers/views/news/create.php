<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Создание новой новости
 */
$this->title = Yii::$app->params['site-name-manager'] . 'Новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index', 'section'=> 'news']];
$this->params['breadcrumbs'][] = 'Новая запись [Публикация]';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
            'model' => $model,
            'status_publish' => $status_publish,
            'notice' => $notice,
            'rubrics' => $rubrics,
            'houses' => $houses,
            'parnters' => $parnters,
    ]) ?>
    
</div>