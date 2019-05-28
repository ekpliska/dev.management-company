<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\models\StatusRequest;
    use app\modules\dispatchers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\dispatchers\widgets\AddSpecialist;
    use app\modules\dispatchers\widgets\ModalWindowsDispatcher;
    use app\modules\dispatchers\widgets\SwitchStatusRequest;


/* 
 * Просмотр заявки
 */
$this->title = "Заявка ID{$request['requests_ident']}";
$this->params['breadcrumbs'][] = ['label' => 'Завяки', 'url' => ['requests/index', 'block' => 'requests']];
$this->params['breadcrumbs'][] = "Заявка ID{$request['requests_ident']}";

// Проверяем для текущей завяки наличие статусов "Закрыто", "Отклонена"
$hide_btn = ($request['status'] == StatusRequest::STATUS_CLOSE || $request['status'] == StatusRequest::STATUS_REJECT) ? false : true;
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div class="requests-view">

        <h1 class="page-header requests-view_title">
            <i class="glyphicon glyphicon-ok <?= $request['is_accept'] ? 'check' : 'uncheck' ?>"></i>&nbsp;&nbsp;Заявка принята
            
            <?php if ($hide_btn) : ?>
                <?= Html::button('<i class="glyphicon glyphicon-bookmark"></i> Отклонить', [
                        'class' => 'settings-record-btn reject-request',
                        'data' => [
                            'status' => StatusRequest::STATUS_REJECT,
                            'request' => $request['requests_id'],
                            'type-request' => 'requests',
                            'target' => '#confirm-reject-request-message',
                            'toggle' => 'modal'
                        ]
                ]) ?>
            <?php endif; ?>
            
        </h1>

        <div class="row row-flex">
            <div class="col-md-7 col-sm-6 col-xs-12 requests-border">
                <div class="content requests-view_body">
                    <h4>
                        <?= $request['type_requests_name'] ?>
                    </h4>
                    <p class="date_requests">
                        <?= FormatHelpers::formatDate($request['created_at'], true, 0, false) ?>
                    </p>

                    <div id="star" data-request="<?= $request['requests_id'] ?>" data-score-reguest="<?= $request['requests_grade'] ?>"></div>

                    <div class="requests__status-block">
                        <span class="badge request-ident">
                            <?= "ID{$request['requests_ident']}" ?>
                        </span>
                        <?= SwitchStatusRequest::widget([
                                'status' => $request['status'],
                                'request_id' => $request['requests_id'],
                                'date_update' => $request['updated_at'],
                                'type_request' => 'requests',
                        ]) ?>
                    </div>


                    <p class="request_text">
                        <?= $request['requests_comment'] ?>
                    </p>

                    <?php if (isset($all_images) && count($all_images) > 0) : ?>
                        <?php foreach ($all_images as $image) : ?>
                            <?= FormatHelpers::formatUrlFileRequest($image['filePath']) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="client_info">
                    <div class="client_info__block">
                        <div>
                            <span class="glyphicon glyphicon-home"></span>
                        </div>
                        <div class="client_info-address text-left">
                            <p>
                                <?= FormatHelpers::formatFullAdress(
                                        $request['houses_gis_adress'], 
                                        $request['houses_street'], 
                                        $request['houses_number'], 
                                        $request['flats_porch'], 
                                        $request['flats_floor'], 
                                        $request['flats_number']) ?>
                            </p>
                        </div>
                        <div class="client_info-contact text-right">
                            <p>
                                <?= $request['requests_phone'] ?>
                            </p>
                            <p>
                                <?= FormatHelpers::formatFullUserName(
                                        $request['clients_surname'], 
                                        $request['clients_name'], 
                                        $request['clients_second_name'], true)
                                ?>
                            </p>
                            </div>
                            <div class="text-right">
                                <span class="glyphicon glyphicon-phone"></span>
                            </div>                            
                        </div>
                    </div>
                    
                    
                    <div class="requests-view__setting">
                        <table class="table table-add-employee">
                            <tr><td id="employee-post">Специалист</td></tr>
                            <tr>
                                <td id="specialist-name">
                                    <?= FormatFullNameUser::nameEmployee($request['surname_s'], $request['name_s'], $request['sname_s'], true) ?>
                                </td>
                            </tr>
                            <?php if ($hide_btn) : ?>
                            <tr>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                            'class' => 'btn blue-btn btn-employee',
                                            'data-request' => $request['requests_id'],
                                            'data-type-request' => 'requests',
                                            'data-employee' => $request['employee_id_s'],
                                            'data-target' => '#add-specialist-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>                    

                </div>
            </div>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <?php if ($request['status'] == StatusRequest::STATUS_CLOSE) : ?>
                    <div class="text-right requests__chat-status">
                        <label class="switch-rule switch-rule-orange">
                            <?= Html::checkbox('chat_status', $request['close_chat'], [
                                    'id' => 'close-chat',
                                    'data-request' => $request['requests_id']])
                            ?>
                            <span class="slider slider-orange round"></span>
                        </label>
                        <span class="__label">Отключить чат</span>
                    </div>
                <?php endif; ?>

                <div class="content requests-view_chat">
                    <?= $this->render('comments/view', [
                        'model' => $model_comment,
                        'comments_find' => $comments_find]) ?>
                </div>
            </div>      
        </div>
    </div>
    
</div>

<?php if ($hide_btn) : ?>
    <?= AddSpecialist::widget() ?>
    <?= ModalWindowsDispatcher::widget(['modal_view' => 'confirm_request_modal']) ?>
<?php endif; ?>
