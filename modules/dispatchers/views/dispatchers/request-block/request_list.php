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
    <?php foreach ($account['request'] as $key_request => $request) : ?>
        <div class="dispatcher_genaral-page__request_block row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 request_block__info">
                <?= StatusHelpers::requestStatusPage($request['status'], $request['created_at']) ?>
                <span class="request_number">
                    <?= "ID{$request['requests_ident']}" ?>
                </span>
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
<?php endif; ?>
        