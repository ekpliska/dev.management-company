<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;
    use app\helpers\FormatHelpers;    
    use app\modules\clients\widgets\RatingRequest;
    use app\helpers\StatusHelpers;
    use app\models\StatusRequest;
    use app\modules\clients\widgets\AlertsShow;

/* 
 * Детали заявки
 */
$this->title = Yii::$app->params['site-name'] . 'Детали заявки ' . $request_info['requests_ident'];
?>

<?= AlertsShow::widget() ?>

<div class="requests-view">
    <h1 class="page-header requests-view_title">
        <i class="glyphicon glyphicon-ok <?= $request_info['is_accept'] ? 'check' : 'uncheck' ?>"></i>&nbsp;&nbsp;Заявка принята
        <?= Html::a('Вернуться к списку заявок', ['requests/index'], ['class' => 'link-comeback pull-right']) ?>
    </h1>
    <div class="row row-flex">
        <div class="col-md-7 col-sm-6 col-xs-12 requests-border">
            <div class="content requests-view_body">
                <h4>
                    <?= $request_info['type_requests_name'] ?>
                </h4>
                <p class="date_requests">
                    <?= FormatHelpers::formatDate($request_info['created_at'], true, 0, false) ?>
                </p>
                
                
                <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>
                    <div class="req-rate-star">
                        <div class="starrr" id="star1">
                            <?= RatingRequest::widget([
                                '_status' => $request_info['status'], 
                                '_request_id' => $request_info['requests_id'],
                                '_score' => $request_info['requests_grade']]) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="requests__status-block">
                    <span class="request-ident">
                        <?= 'ID ' . $request_info['requests_ident'] ?>
                    </span>

                    <?= StatusHelpers::requestStatusPage($request_info['status'], $request_info['updated_at']) ?>
                </div>
                    
                <p class="request_text">
                    <?= $request_info['requests_comment'] ?>
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
                                <?= Yii::$app->userProfile->getFullAdress($this->context->_current_account_id)?></span>
                            </p>
                        </div>
                        <div class="client_info-contact text-right">
                            <p>
                                <?= $request_info['requests_phone'] ?></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="glyphicon glyphicon-phone"></span>
                        </div>                            
                    </div>
                </div>

                <div class="request-body-rate">
                    <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE && empty($request_info['requests_grade'])) : ?>
                        <?= Html::a('Оценить', 
                                ['requests/add-grade', 'request' => $request_info['requests_id']], 
                                ['class' => 'btn btn-link white-to-blue-link' ,'id' => 'add-rate']) 
                        ?>
                    <?php endif; ?> 
                </div>                
            </div>
        </div>
        <div class="col-md-5 col-sm-6 col-xs-12">
            <div class="content requests-view_chat">
                
                <?= $this->render('form/_comment', [
                        'model' => $comments, 
                        'comments_find' => $comments_find, 
                        'request_id' => $request_info['requests_id'],
                        'status_chat' => $request_info['close_chat'],
                ]); ?>                  
            </div>
        </div>      
    </div>
</div>

<?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>
<?php
    Modal::begin([
        'id' => 'add-grade-modal',
        'header' => 'Оцените качество обслуживания',
        'closeButton' => [
            'class' => 'close modal-close-btn grade-modal__close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

<?php Modal::end(); ?>
<?php endif; ?>