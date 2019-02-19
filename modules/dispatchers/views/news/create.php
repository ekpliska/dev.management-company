<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Создание новой новости
 */
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['news/index']];
$this->params['breadcrumbs'][] = 'Новая запись [Публикация]';
?>

<div class="dispatcher-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
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