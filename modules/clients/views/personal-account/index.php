<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\clients\widgets\AlertsShow;
    
/* 
 * Лицевой счет / Общая информация
 */
    
$this->title = Yii::$app->params['site-name'] . 'Лицевой счет';
$this->params['breadcrumbs'][] = 'Общая информация';
?>

<?= Breadcrumbs::widget([
        'homeLink' => ['label' => 'ELSA', 'url' => ['clients/index']],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options' => [
            'class' => 'breadcrumb breadcrumb-padding'
        ],
]) ?>

<?= AlertsShow::widget(); ?>

<div class="paid-account-page">
    
    <?= $this->render('data/list', ['account_info' => $account_info]); ?>
    
    <?php if (Yii::$app->user->can('clients')) : ?>
        <div class="paid-account-page_btn text-center">
            <?= Html::button('Добавить лицевой счет', [
                    'class' => 'btn blue-btn add-acc-btn',
                    'data-toggle' => 'modal',
                    'data-target' => '#create-account-modal']) ?>
        </div>
        <?= $this->render('form/create_account', ['model' => $model]) ?>
    <?php endif; ?>
    
</div>