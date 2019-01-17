<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\models\StatusRequest;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;
    use app\modules\managers\widgets\AddEmployee;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Просмотр и редактирование заявки на платную услугу
 */
$this->title = "Заявка на платную услугу ID{$paid_request['number']}";
$this->params['breadcrumbs'][] = ['label' => 'Платные услуги', 'url' => ['requests/paid-services']];
$this->params['breadcrumbs'][] = "Заявка на платную услугу ID{$paid_request['number']}";
?>

<div class="manager-main">
    <span id="status_request" hidden><?= $request['status'] ?></span>
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="requests-view">
        
        <h1 class="page-header requests-view_title">
            <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Удалить заявку', [
                    'id' => 'delete-request',
                    'class' => 'btn settings-record-btn',
                    'data-target' => '#delete-request-message',
                    'data-toggle' => 'modal',
                    'data-request-type' => 'requests',
                    'data-request' => $paid_request['id'],
            ]) ?>
                        
            <?= Html::button('<i class="glyphicon glyphicon-bookmark"></i> Отклонить', [
                    'class' => 'btn settings-record-btn reject-request' . ($paid_request['status'] == StatusRequest::STATUS_REJECT ? ' settings-btn-hide' : ''),
                    'data' => [
                        'status' => StatusRequest::STATUS_REJECT,
                        'request' => $paid_request['id'],
                    ]
            ]) ?>
            
        </h1>
        
        <div class="row row-flex">
            <div class="col-md-12 col-sm-12 col-xs-12 requests-border">
                <div class="content requests-view_body">
                    <h4>
                        <?= $paid_request['category'] . '/' . $paid_request['services_name'] ?>
                    </h4>
                    <p class="date_requests">
                        <?= FormatHelpers::formatDate($paid_request['date_cr'], true, 0, false) ?>
                    </p>

                    <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Внести изменения', 
                            ['edit-request', 'request_id' => $paid_request['id']], 
                            ['class' => 'btn edit-request-btn']) ?>

                    <div class="requests__status-block">
                        <span class="badge request-ident">
                            <?= "ID{$paid_request['number']}" ?>
                            <?= $paid_request['status'] ?>
                        </span>
                        <?= SwitchStatusRequest::widget([
                                'view_name' => 'request',
                                'status' => $paid_request['status'],
                                'request_id' => $paid_request['id'],
                                'date_update' => $paid_request['date_up'],
                            ]) ?>
                    </div>


                    <p class="request_text">
                        <?= $paid_request['text'] ?>
                    </p>

                    <div class="client_info">
                        <div class="col-lg- col-sm-6 col-md-6 text-left">
                            <div class="client_info-image">
                                <span class="glyphicon glyphicon-home"></span>
                            </div>
                            <span class="client_info-text">
                                <?= FormatHelpers::formatFullAdress(
                                        $paid_request['gis_adress'], 
                                        $paid_request['houses_number'], 
                                        $paid_request['porch'], 
                                        $paid_request['floor'], 
                                        $paid_request['flat']) ?>
                            </span>

                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 text-left">
                            <div class="client_info-image">
                                <i class="glyphicon glyphicon-phone"></i>
                            </div>
                            <span class="client_info-text"><?= $paid_request['phone'] ?></span>
                        </div>
                    </div>

                    <div class="requests-view__setting">
                        <table class="table table-voting-results">
                            <tr>
                                <td id="dispatcher-name">
                                    <?= FormatFullNameUser::fullNameEmployee(
                                            $paid_request['employee_id_d'], true, true, [
                                                $paid_request['surname_d'], $paid_request['name_d'], $paid_request['sname_d']
                                            ]) ?>
                                </td>
                                <td id="specialist-name">
                                    <?= FormatFullNameUser::fullNameEmployee(
                                            $paid_request['employee_id_s'], false, true, [
                                                $paid_request['surname_s'], $paid_request['name_s'], $paid_request['sname_s']
                                            ]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить диспетчера', [
                                            'class' => 'btn blue-border-btn btn-dispatcher',
                                            'data-type-request' => 'request',
                                            'data-employee' => $paid_request['employee_id_d'],
                                            'data-target' => '#add-dispatcher-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                            'class' => 'btn blue-border-btn',
                                            'data-type-request' => 'request',
                                            'data-employee' => $paid_request['employee_id_s'],
                                            'data-target' => '#add-specialist-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>
            </div>        
        
        
    </div>
</div>
<?= AddEmployee::widget() ?>
<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $paid_request['category'] ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $paid_request['services_name'] ?>
                    <br />
                    <?= FormatHelpers::formatDate($paid_request['date_cr']) ?> 
                    <div id="star" data-request="<?= $paid_request['id'] ?>" data-score-reguest="<?= $paid_request['grade'] ?>"></div>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $paid_request['number'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    
                    <?= SwitchStatusRequest::widget([
                            'view_name' => 'paid_request',
                            'status' => $paid_request['status'],
                            'request_id' => $paid_request['id']]) ?>
                    
                    <?= FormatHelpers::formatDate($paid_request['date_up']) ?>   
                    <hr />                 
                </div>
                
                <div class="col-md-12">
                    <?= $paid_request['text'] ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $paid_request['town'], 
                            $paid_request['street'], 
                            $paid_request['number_house'], 
                            $paid_request['porch'], 
                            $paid_request['floor'], 
                            $paid_request['flat']) ?>
                </div>
                <div class="col-md-6">
                    <?= $paid_request['phone'] ?>
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($paid_request['phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <div id="dispatcher-name">
                            <?= FormatFullNameUser::fullNameEmployee($paid_request['dispatcher'], true, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить диспетчера', [
                            'class' => 'btn btn-default btn-dispatcher',
                            'data-type-request' => 'paid-request',
                            'data-employee' => $paid_request['dispatcher'],
                            'data-target' => '#add-dispatcher-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <div id="specialist-name">
                            <?= FormatFullNameUser::fullNameEmployee($paid_request['specialist'], false, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить специалиста', [
                            'class' => 'btn btn-default',
                            'data-type-request' => 'paid-request',
                            'data-employee' => $paid_request['specialist'],
                            'data-target' => '#add-specialist-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <br />
                        <?= Html::button('Отклонить', ['class' => 'btn btn-danger reject-request']) ?>
                    </div>
                </div>
                
            </div>
        </div>        
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">Комменатрии к заявке</div>
            <div class="panel-body">
                #TODO
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <?= AddEmployee::widget() ?>
</div>


<?php
$grade = $paid_request['grade'] ? $paid_request['grade'] : 0; 
$this->registerJs("
$('div#star').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?> 