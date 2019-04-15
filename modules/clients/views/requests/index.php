<?php
    
    use yii\helpers\Html;
    use app\modules\clients\widgets\StatusRequestWidget;
    
/* 
 * Заявки (Общая страница)
 */
$this->title = Yii::$app->params['site-name'] . 'Заявки';
?>

<div class="requests-page">
    
    <div>
        <?= StatusRequestWidget::widget(['account_id' => $this->context->_current_account_id]) ?>
    </div>

    <?= $this->render('data/grid', ['all_requests' => $all_requests]); ?>
    
    <?= Html::button('', [
            'class' => 'create-request-btn pull-right', 
            'data-toggle' => 'modal', 
            'data-target' => '#add-request-modal']) ?>
</div>

<?= $this->render('form/add-request', ['model' => $model, 'type_requests' => $type_requests]) ?>

<?php
$this->registerCss("
        .navbar-menu {
//            box-shadow: none;
        }
        .navbar_repusets {
            box-shadow: inset 0px 2px 2px -1px #555;
        }
");
?>