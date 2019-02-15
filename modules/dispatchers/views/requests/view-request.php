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
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
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
                        <?= StatusHelpers::requestStatusPage($request['status'], $request['updated_at']) ?>
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
                        <div class="col-lg- col-sm-6 col-md-6 text-left">
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
                                            'class' => 'btn blue-btn',
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
