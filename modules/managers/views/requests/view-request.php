<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;
    use app\modules\managers\widgets\AddEmployee;

/* 
 * Просмотр и редактирование заявки
 */
$this->title = "Заявка ID{$request['requests_ident']}";
$this->params['breadcrumbs'][] = ['label' => 'Завяки', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = "Заявка ID{$request['requests_ident']}";
?>

<div class="manager-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
<div class="requests-view">
    
    <h1 class="page-header requests-view_title">
        <i class="glyphicon glyphicon-ok <?= $request_info['is_accept'] ? 'check' : 'uncheck' ?>"></i>&nbsp;&nbsp;Заявка принята
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
                
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Внести изменения', ['/'], ['class' => 'btn edit-request-btn']) ?>
                
                <div class="requests__status-block">
                    <span class="badge request-ident">
                        <?= "ID{$request['requests_ident']}" ?>
                        <?= $request['status'] ?>
                    </span>
                    <?= SwitchStatusRequest::widget([
                            'view_name' => 'request',
                            'status' => $request['status'],
                            'request_id' => $request['requests_id'],
                            'date_update' => $request['updated_at'],
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
                    <table class="table table-voting-results">
                        <tr>
                            <td id="dispatcher-name">
                                <?= FormatFullNameUser::fullNameEmployer($request['requests_dispatcher_id'], true, true) ?>
                            </td>
                            <td id="specialist-name">
                                <?= FormatFullNameUser::fullNameEmployer($request['requests_specialist_id'], false, true) ?>
                            </td>
                            <td rowspan="2">
                                <?= Html::button('Отклонить', ['class' => 'btn btn delete-record-btn reject-request']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить диспетчера', [
                                        'class' => 'btn blue-border-btn btn-dispatcher',
                                        'data-type-request' => 'request',
                                        'data-employee' => $request['requests_dispatcher_id'],
                                        'data-target' => '#add-dispatcher-modal',
                                        'data-toggle' => 'modal']) ?>
                            </td>
                            <td>
                                <?= Html::button('<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Назначить специалиста', [
                                        'class' => 'btn blue-border-btn',
                                        'data-type-request' => 'request',
                                        'data-employee' => $request['requests_specialist_id'],
                                        'data-target' => '#add-specialist-modal',
                                        'data-toggle' => 'modal']) ?>
                            </td>
                        </tr>
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
    
<?php /*    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $request['is_accept'] ? 'Заявка принята' : 'Заявка не принята' ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $request['type_requests_name'] ?>
                    <br />
                    <?= FormatHelpers::formatDate($request['created_at']) ?> 
                    <div id="star" data-request="<?= $request['requests_id'] ?>" data-score-reguest="<?= $request['requests_grade'] ?>"></div>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $request['requests_ident'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    
                    <?= SwitchStatusRequest::widget([
                            'view_name' => 'request',
                            'status' => $request['status'],
                            'request_id' => $request['requests_id']]) ?>
                    
                    <?= FormatHelpers::formatDate($request['updated_at']) ?>   
                    <hr />                 
                </div>
                
                <div class="col-md-12">
                    <?= $request['requests_comment'] ?>
                    <hr />
                    Прикрепленные файлы: <br />
                    <?php if (isset($all_images)) : ?>
                        <?php foreach ($all_images as $image) : ?>
                            <?= FormatHelpers::formatUrlFileRequest($image->getImagePath($image->filePath)) ?>
                            <br />
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $request['estate_town'], 
                            $request['houses_street'], 
                            $request['houses_number_house'], 
                            $request['flats_porch'], 
                            $request['flats_floor'], 
                            $request['flats_number']) ?>
                </div>
                <div class="col-md-6">
                    <?= $request['requests_phone'] ?>
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($request['requests_phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <div id="dispatcher-name">
                            <?= FormatFullNameUser::fullNameEmployer($request['requests_dispatcher_id'], true, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить диспетчера', [
                            'class' => 'btn btn-default btn-dispatcher',
                            'data-type-request' => 'request',
                            'data-employee' => $request['requests_dispatcher_id'],
                            'data-target' => '#add-dispatcher-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <div id="specialist-name">
                            <?= FormatFullNameUser::fullNameEmployer($request['requests_specialist_id'], false, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить специалиста', [
                            'class' => 'btn btn-default',
                            'data-type-request' => 'request',
                            'data-employee' => $request['requests_specialist_id'],
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
                <?= $this->render('comments/view', [
                    'model' => $model_comment,
                    'comments_find' => $comments_find]) ?>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <?= AddEmployee::widget() */ ?>
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