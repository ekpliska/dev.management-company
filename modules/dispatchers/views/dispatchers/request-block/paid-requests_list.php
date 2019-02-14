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
<?php if (isset($user_lists) && !empty($user_lists)) : ?>
<?php foreach ($user_lists[$key]['personalAccount'] as $key_account => $account) : ?>
    <?php foreach ($account['paidRequest'] as $key_request => $paid_request) : ?>
        <div class="dispatcher_genaral-page__request_block">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 request_block__info">
                <?= StatusHelpers::requestStatusPage($paid_request['status'], $paid_request['created_at']) ?>
                <span class="request_number">
                    <?= "ID{$paid_request['services_number']}" ?>
                </span>
                <?= Html::a('Перейти к заявке', ['/'], ['class' => 'request__link-view']) ?>
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
<?php endif; ?>
        