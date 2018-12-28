<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Голосование, создание голосования
 */

$this->title = 'Голосование';
$this->title = Yii::$app->params['site-name-manager'] . 'Голосование';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = 'Новая запись [Голосование]';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
        'model' => $model,
        'type_voting' => $type_voting,
        'houses_array' => $houses_array,
    ]) ?>
    
</div>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
        'model' => $model,
        'type_voting' => $type_voting,
    ]) ?>
    
</div>
 * 
 */ ?>