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
$this->params['breadcrumbs'][] = ['label' => 'Платные услуги', 'url' => ['paid-requests/index']];
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
                    'class' => 'settings-record-btn',
                    'data-target' => '#delete-request-message',
                    'data-toggle' => 'modal',
                    'data-request-type' => 'paid-requests',
                    'data-request' => $paid_request['id'],
            ]) ?>
                        
            <?= Html::button('<i class="glyphicon glyphicon-bookmark"></i> Отклонить', [
                    'class' => 'settings-record-btn reject-request' . ($paid_request['status'] == StatusRequest::STATUS_REJECT ? ' settings-btn-hide' : ''),
                    'data' => [
                        'status' => StatusRequest::STATUS_REJECT,
                        'request' => $paid_request['id'],
                        'type-request' => 'paid-requests',
                    ]
            ]) ?>
            
        </h1>
        
        <div class="row row-flex">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="content requests-view_body">
                    <h4>
                        <?= $paid_request['category'] . '<span class="label">' . $paid_request['services_name'] . '</span>' ?>
                    </h4>
                    <p class="date_requests">
                        <?= FormatHelpers::formatDate($paid_request['date_cr'], true, 0, false) ?>
                    </p>

                    <div class="page-btn-block text-left">
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Внести изменения', 
                                ['edit-paid-request', 'request_id' => $paid_request['id']], 
                                ['class' => 'edit-request-btn']) ?>
                    </div>

                    <div class="requests__status-block">
                        <span class="badge request-ident">
                            <?= "ID{$paid_request['number']}" ?>
                            <?= $paid_request['status'] ?>
                        </span>
                        <?= SwitchStatusRequest::widget([
                                'status' => $paid_request['status'],
                                'request_id' => $paid_request['id'],
                                'date_update' => $paid_request['date_up'],
                                'type_request' => 'paid-requests',
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
                                                $paid_request['surname_d'], $paid_request['name_d'], $paid_request['second_name_d']
                                            ]) ?>
                                </td>
                                <td id="specialist-name">
                                    <?= FormatFullNameUser::fullNameEmployee(
                                            $paid_request['employee_id_s'], false, true, [
                                                $paid_request['surname_s'], $paid_request['name_s'], $paid_request['second_name_s']
                                            ]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить диспетчера', [
                                            'class' => 'btn blue-btn btn-dispatcher',
                                            'data-type-request' => 'paid-requests',
                                            'data-employee' => $paid_request['employee_id_d'],
                                            'data-target' => '#add-dispatcher-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                            'class' => 'btn blue-btn btn-specialist',
                                            'data-type-request' => 'paid-requests',
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
</div>
<?= AddEmployee::widget() ?>
    
<?php
    /* Модальное окно для загрузки формы редактирования заявки */
    Modal::begin([
        'id' => 'edit-requests',
        'header' => 'Редактирование заявки',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>
<?php Modal::end(); ?>    

<?= ModalWindowsManager::widget(['modal_view' => 'delete_request_modal']) ?>