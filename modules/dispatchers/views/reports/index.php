<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\dispatchers\widgets\ReportBar;
    
/*
 * Отчеты, главная страница
 */
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Отчеты';
$this->params['breadcrumbs'][] = 'Отчеты';
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row reports">
        
        <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="reports_title">
                <ul class="nav nav-pills reports__type-report">
                    <li class="<?= $block == 'requests' ? 'active' : '' ?>">
                        <?= Html::a('Заявки', ['reports/index', 'block' => 'requests'], ['class' => '']) ?>
                    </li>
                    <li class="<?= $block == 'paid-requests' ? 'active' : '' ?>">
                        <?= Html::a('Платные услуги', ['reports/index', 'block' => 'paid-requests'], ['class' => '']) ?>
                    </li>
                    
                    <?= Html::a('В PDF <i class="glyphicon glyphicon-export"></i>', ['reports/create-report', 'block' => $block], [
                            'target' => '_blank',
                            'class' => 'btn-to-pdf pull-right']) ?>
                </ul>
            </div>
            <div class="reports__content">
                <?= ReportBar::widget(['type' => $block]) ?>
                
                <div class="panel-group requests__content">
                    <?= $this->render("data/grid_{$block}", [
                            'results' => $results,
                    ]) ?>
                </div>
                
            </div>
        </div>
        
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <div class="reports_title">
                Фильтр
            </div>
            <div class="reports__filtr_form">

                <?= $this->render("form/_search-{$block}", [
                        'search_model' => $search_model,
                        'type_requests' => $type_requests,
                        'name_services' => $name_services,
                        'servise_category' => $servise_category,
                        'specialist_lists' => $specialist_lists,
                        'status_list' => $status_list]) ?>

            </div>
        </div>        
        
    </div>
</div>
