<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\models\StatusRequest;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\dispatchers\widgets\AddSpecialist;
    use app\modules\dispatchers\widgets\ModalWindowsDispatcher;


/* 
 * Просмотр и редактирование заявки
 */
$this->title = "Заявка на платную услугу ID{$paid_request['number']}";
$this->params['breadcrumbs'][] = ['label' => 'Завяки', 'url' => ['requests/index', 'block' => 'paid-requests']];
$this->params['breadcrumbs'][] = "Заявка на платную услугу ID{$paid_request['number']}";

// Проверяем для текущей завяки наличие статусов "Закрыто", "Отклонена"
$hide_btn = ($paid_request['status'] == StatusRequest::STATUS_CLOSE || $paid_request['status'] == StatusRequest::STATUS_REJECT) ? false : true;
?>

<div class="dispatcher-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <div class="requests-view">
        <h1 class="page-header requests-paid-view_title">
            <?= $paid_request['category'] . '<span class="label_service-name">' . $paid_request['services_name'] . '</span>' ?>
            <?php if ($hide_btn) : ?>
            <?= Html::button('<i class="glyphicon glyphicon-bookmark"></i> Отклонить', [
                    'class' => 'settings-record-btn reject-request',
                    'data' => [
                        'status' => StatusRequest::STATUS_REJECT,
                        'request' => $paid_request['id'],
                        'type-request' => 'paid-requests',
                        'target' => '#confirm-reject-request-message',
                        'toggle' => 'modal'
                    ]
            ]) ?>
            <?php endif; ?>
            
        </h1>
        
        <div class="row row-flex">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="content requests-view_body">
                    <p class="date_requests">
                        <?= FormatHelpers::formatDate($paid_request['date_cr'], true, 0, false) ?>
                    </p>

                    <div class="requests__status-block">
                        <span class="badge request-ident">
                            <?= "ID{$paid_request['number']}" ?>
                        </span>
                        <?= StatusHelpers::requestStatusPage($paid_request['status'], $paid_request['date_up']) ?>
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
                        <table class="table table-add-employee">
                            <tr><td id="employee-post">Специалист</td></tr>
                            <tr>
                                <td id="specialist-name">
                                    <?= FormatFullNameUser::nameEmployee($paid_request['surname_s'], $paid_request['name_s'], $paid_request['second_name_s'], true) ?>
                                </td>
                            </tr>
                            <?php if ($hide_btn) : ?>
                            <tr>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                            'class' => 'btn blue-btn btn-employee',
                                            'data-request' => $paid_request['id'],
                                            'data-type-request' => 'paid-requests',
                                            'data-employee' => $paid_request['employee_id_s'],
                                            'data-target' => '#add-specialist-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>


                </div>
            </div>        
        
        
        </div>
    </div>
</div>

<?php if ($hide_btn) : ?>
    <?= AddSpecialist::widget() ?>
<?php endif; ?>

<?= ModalWindowsDispatcher::widget(['modal_view' => 'confirm_request_modal']) ?>
