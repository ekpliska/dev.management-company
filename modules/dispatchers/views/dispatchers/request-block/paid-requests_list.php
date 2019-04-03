<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\models\StatusRequest;

/* 
 * Список заявок конечного пользователя
 */
// На главной странице выводим все заявки первого пользователя по списку (список слева)
$key = 0;
$status_use = [];
?>

<?php if (isset($user_lists) && !empty($user_lists)) : ?>
    <?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : // foreach 1 ?>
        <?php foreach ($account['paidRequest'] as $key_request => $paid_request) : // foreach 2 ?>
            <div class="panel panel-default panel__request_block">
                <?php if (!in_array($paid_request['status'], $status_use)) : ?>
                <?php $status_use[] = $paid_request['status']; ?>
                <div class="panel-heading panel-heading-<?= $paid_request['status'] ?>">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="<?= "#status-{$paid_request['status']}" ?>">
                            <?= StatusRequest::statusName($paid_request['status']) ?> <i class="fa fa-sort-desc"></i>
                        </a>
                    </h4>
                </div>
                <?php endif; ?>
                
                <div class="dispatcher_genaral-page__request_block row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__info text-center">
                        <?= Html::a("ID{$paid_request['services_number']}", [
                                'requests/view-paid-request', 'request_number' => $paid_request['services_number']], [
                                    'class' => 'request__link-view']) ?>
                        <p class="date-create-request">
                            <?= FormatHelpers::formatDate($paid_request['created_at'], true, 1) ?>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__adress text-right">
                        <?= FormatHelpers::formatFullAdress(
                                $account['flat']['house']['houses_gis_adress'],
                                $account['flat']['house']['houses_number'],
                                false, false, 
                                $account['flat']['flats_number']) ?>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 request_block__content">
                        <?= FormatHelpers::shortTitleOrText($paid_request['services_comment'], 90) ?>
                    </div>
                </div>
                
            </div>
        <?php endforeach; // foreach 2 ?>
        <?php endforeach; // foreach 1 ?>
<?php else : ?>
    <div class="notice info">
        <p>Список заявок пуст.</p>
    </div>
<?php endif; ?>


<?php /* if (isset($user_lists) && !empty($user_lists)) : ?>
<?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : ?>
    <?php foreach ($account['paidRequest'] as $key_request => $paid_request) : ?>
        <div class="dispatcher_genaral-page__request_block">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 request_block__info">
                <?= StatusHelpers::requestStatusPage($paid_request['status'], $paid_request['created_at']) ?>
                <span class="request_number">
                    <?= "ID{$paid_request['services_number']}" ?>
                </span>
                <?= Html::a('Перейти к заявке', [=> $paid_request['services_number']], ['class' => 'request__link-view']) ?>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 request_block__adress text-right">
                <?= FormatHelpers::formatFullAdress(
                        $account['flat']['house']['houses_gis_adress'],
                        $account['flat']['house']['houses_number'],
                        false, false, 
                        $account['flat']['flats_number']) ?>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 request_block__content">
                <?= FormatHelpers::shortTitleOrText($paid_request['services_comment'], 90) ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Список заявок на платные услуги пуст.</p>
    </div>
<?php endif; */ ?>
        