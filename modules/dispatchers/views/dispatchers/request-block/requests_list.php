<?php

    use yii\helpers\Html;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatHelpers;

/* 
 * Список заявок конечного пользователя
 */
// На главной странице выводим все заявки первого пользователя по списку (список слева)
$key = 0;
?>

<?php if (!empty($status_list)) : ?>
<div class="panel-group" id="accordion">
    <?php foreach ($status_list as $key_status => $status) : ?>
    <div class="panel-group">
        <div class="panel panel-default panel__request_block">
            <div class="panel-heading panel-heading-<?= $key_status ?>">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="<?= "#status-{$key_status}" ?>">
                        <?= $status ?> <i class="fa fa-sort-desc"></i>
                    </a>
                </h4>
            </div>
            <div id="<?= "status-{$key_status}" ?>" class="panel-collapse collapse">
                <?php if (isset($user_lists) && !empty($user_lists)) : ?>
                <?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : ?>
                    <?php foreach ($account['request'] as $key_request => $request) : ?>
                        <?php if ($request['status'] == $key_status) : ?>
                        <div class="dispatcher_genaral-page__request_block row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__info text-center">
                                <?= Html::a("ID{$request['requests_ident']}", [
                                        'requests/view-request', 'request_number' => $request['requests_ident']], [
                                            'class' => 'request__link-view']) ?>
                                <p class="date-create-request">
                                    <?= FormatHelpers::formatDate($request['created_at'], true, 1) ?>
                                </p>
                                <?php // = Html::a("ID{$request['requests_ident']}", ['requests/view-request', 'request_number' => $request['requests_ident']], ['class' => 'request__link-view']) ?>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__adress text-right">
                                <?= FormatHelpers::formatFullAdress(
                                        $account['flat']['house']['houses_gis_adress'],
                                        $account['flat']['house']['houses_number'],
                                        false, false, 
                                        $account['flat']['flats_number']) ?>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 request_block__content">
                                <?= FormatHelpers::shortTitleOrText($request['requests_comment'], 90) ?>
                                <div class="request_block__content_img">
                                    <?= FormatHelpers::imageRequestListByDispatcher($request['image']) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php else : ?>
                    <div class="notice info">
                        <p>Список заявок пуст.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>


<?php /* if (isset($user_lists) && !empty($user_lists)) : ?>
<?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : ?>
    <?php foreach ($account['request'] as $key_request => $request) : ?>
        <div class="dispatcher_genaral-page__request_block row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 request_block__info">
                <?= StatusHelpers::requestStatusPage($request['status'], $request['created_at']) ?>
                <span class="request_number">
                    <?= "ID{$request['requests_ident']}" ?>
                </span>
                <?= Html::a('Перейти к заявке', ['requests/view-request', 'request_number' => $request['requests_ident']], ['class' => 'request__link-view']) ?>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__adress text-right">
                <?= FormatHelpers::formatFullAdress(
                        $account['flat']['house']['houses_gis_adress'],
                        $account['flat']['house']['houses_number'],
                        false, false, 
                        $account['flat']['flats_number']) ?>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 request_block__content">
                <?= FormatHelpers::shortTitleOrText($request['requests_comment'], 90) ?>
                <div class="request_block__content_img">
                    <?= FormatHelpers::imageRequestListByDispatcher($request['image']) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
<?php else : ?>
    <div class="notice info">
        <p>Список заявок пуст.</p>
    </div>
<?php endif; */ ?>
        