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
 * Просмотр и редактирование заявки
 */
$this->title = "Заявка ID{$request['requests_ident']}";
$this->params['breadcrumbs'][] = ['label' => 'Завяки', 'url' => ['requests/index']];
$this->params['breadcrumbs'][] = "Заявка ID{$request['requests_ident']}";
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
            <i class="glyphicon glyphicon-ok <?= $request['is_accept'] ? 'check' : 'uncheck' ?>"></i>  
            <span class="check_message">Заявка принята</span>
            
            <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Удалить заявку', [
                    'id' => 'delete-request',
                    'class' => 'settings-record-btn',
                    'data-target' => '#delete-request-message',
                    'data-toggle' => 'modal',
                    'data-request-type' => 'requests',
                    'data-request' => $request['requests_id'],
            ]) ?>
                        
            <?= Html::button('<i class="glyphicon glyphicon-bookmark"></i> Отклонить', [
                    'class' => 'settings-record-btn reject-request' . ($request['status'] == StatusRequest::STATUS_REJECT ? ' settings-btn-hide' : ''),
                    'data' => [
                        'status' => StatusRequest::STATUS_REJECT,
                        'request' => $request['requests_id'],
                        'type-request' => 'requests',
                    ]
            ]) ?>
            
            <?= Html::a('<i class="glyphicon glyphicon-star"></i> Посмотреть отзыв', ['show-grade-modal', 'request_id' => $request['requests_id']], [
                    'id' => 'show-grade-btn',
                    'class' => 'settings-record-btn' . ($request['status'] != StatusRequest::STATUS_CLOSE ? ' settings-btn-hide' : ''),
            ]) ?>
            
        </h1>

        <div class="row">
            <div class="col-lg-7 col-md-8 col-sm-12 col-xs-12 requests-border">
                <div class="content requests-view_body">
                    <h4>
                        <?= $request['type_requests_name'] ?>
                    </h4>
                    <p class="date_requests">
                        <?= FormatHelpers::formatDate($request['created_at'], true, 0, false) ?>
                    </p>

                    <div id="star" data-request="<?= $request['requests_id'] ?>" data-score-reguest="<?= $request['requests_grade'] ?>"></div>

                    <div class="page-btn-block text-left">
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Внести изменения', 
                                ['edit-request', 'request_id' => $request['requests_id']], 
                                ['class' => 'edit-request-btn']) ?>
                    </div>
                
                    <div class="requests__status-block _display-fix">
                        <span class="request-ident">
                            <?= "ID{$request['requests_ident']}" ?>
                            <?= $request['status'] ?>
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
                        <div class="col-lg-6 col-sm-6 col-md-6 text-left">
                            <div class="client_info-image">
                                <span class="glyphicon glyphicon-home"></span>
                            </div>
                            <span class="client_info-text">
                                <?= FormatHelpers::formatFullAdress(
                                        $request['houses_gis_adress'], 
                                        $request['houses_number'], 
                                        $request['flats_porch'], 
                                        $request['flats_floor'], 
                                        $request['flats_number']) ?>
                            </span>

                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 text-left">
                            <div class="client_info-image">
                                <i class="glyphicon glyphicon-phone"></i>
                            </div>
                            <span class="client_info-text"><?= $request['requests_phone'] ?></span>
                        </div>
                    </div>

                    <div class="requests-view__setting">
                        <table class="table table-voting-results">
                            <tr>
                                <td id="dispatcher-name">
                                    <?= FormatFullNameUser::fullNameEmployee(
                                            $request['employee_id_d'], true, true, [
                                                $request['surname_d'], $request['name_d'], $request['sname_d']
                                            ]) ?>
                                </td>
                                <td id="specialist-name">
                                    <?= FormatFullNameUser::fullNameEmployee(
                                            $request['employee_id_s'], false, true, [
                                                $request['surname_s'], $request['name_s'], $request['sname_s']
                                            ]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить диспетчера', [
                                            'class' => 'btn blue-btn btn-dispatcher',
                                            'data-type-request' => 'requests',
                                            'data-employee' => $request['employee_id_d'],
                                            'data-target' => '#add-dispatcher-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                                <td>
                                    <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                            'class' => 'btn blue-btn btn-specialist',
                                            'data-type-request' => 'requests',
                                            'data-employee' => $request['employee_id_s'],
                                            'data-target' => '#add-specialist-modal',
                                            'data-toggle' => 'modal']) ?>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>
            </div>
            <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
                <div class="content requests-view_chat">

                    <?= $this->render('comments/view', [
                        'model' => $model_comment,
                        'comments_find' => $comments_find]) ?>

                </div>
            </div>      
        </div>
    </div>
</div>

<?= AddEmployee::widget() ?>

<?php
$grade = $request['requests_grade'] ? $request['requests_grade'] : 0; 
$this->registerJs("
$('div#star').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?>

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

<?php
    /* Модальное окно для просмотра оценки заявки, поставленной пользователем */
    Modal::begin([
        'id' => 'show-grade-modal',
        'header' => "Просмотр отзыва, заявка ID{$request['requests_ident']}",
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
    ]);
?>
<?php Modal::end(); ?>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_request_modal']) ?>