<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\models\StatusRequest;

/* 
 * Список заявок конечного пользователя
 */
// На главной странице выводим все заявки первого пользователя по списку (список слева)
$key = 0;
?>

<?php if (isset($user_lists) && !empty($user_lists)) : ?>
    <?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : // foreach 1 ?>
    <?php $status_use = []; ?>
        <p class="account-title">
            <?= count($account['request']) > 0 ? "Лицевой счет {$account['account_number']}" : '' ?>
        </p>
        <?php foreach ($account['request'] as $key_request => $request) : // foreach 2 ?>
            <?php if (!in_array($request['status'], $status_use)) : ?>
            <div class="panel panel-default panel__request_block">
                <?php $status_use[] = $request['status']; ?>
                <div class="panel-heading panel-heading-<?= $request['status'] ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="<?= "#status-{$key_account}-{$request['status']}" ?>">
                            <?= StatusRequest::statusName($request['status']) ?> <i class="fa fa-sort-desc"></i>
                        </a>
                    </h4>
                </div>
                <div id="<?= "status-{$key_account}-{$request['status']}" ?>" class="panel-collapse collapse">
            <?php endif; ?>
                <div class="dispatcher_genaral-page__request_block row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__info text-center">
                        <?= Html::a("ID{$request['requests_ident']}", [
                                'requests/view-request', 'request_number' => $request['requests_ident']], [
                                    'class' => 'request__link-view']) ?>
                        <p class="date-create-request">
                            <?= FormatHelpers::formatDate($request['created_at'], true, 1) ?>
                        </p>
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
                <?php if ($request['status'] != $account['request'][$key_request + 1]['status']) : ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; // foreach 2 ?>
    <?php endforeach; // foreach 1 ?>
<?php else : ?>
    <div class="notice info">
        <p>Список заявок пуст.</p>
    </div>
<?php endif; ?>