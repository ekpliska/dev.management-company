<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    
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
                    <li class="active">
                        <?= Html::a('Заявки', ['reports/index', 'block' => 'requests'], ['class' => '']) ?>
                    </li>
                    <li>
                        <?= Html::a('Платные услуги', ['reports/index', 'block' => 'paid-requests'], ['class' => '']) ?>
                    </li>
                    
                    <?= Html::a('В PDF <i class="glyphicon glyphicon-export"></i>', ['reports/create-report', 'block' => $block], ['class' => 'btn-to-pdf pull-right']) ?>
                </ul>
            </div>
            <div class="reports__content">
                
                <div class="panel-group requests__info">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">
                                    Оперативная информация 
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-md-2">1</div>
                                <div class="col-md-2">2</div>
                                <div class="col-md-2">3</div>
                                <div class="col-md-2">4</div>
                                <div class="col-md-2">5</div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
