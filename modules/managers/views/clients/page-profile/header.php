<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Шапка профиля собсвенника
 * Подменю
 */
?>
<div class="profile-bg text-center">
    <div class="col-md-3 col-xs-6 face-user">
        <?= Html::img($user_info->user_photo, [
                'class' => 'img-rounded',
                'alt' => 'user-photo',
        ]) ?>
    </div>
    <div class="col-md-8 col-xs-6 text-left user-info-block">
        <p class="user-info-block_name">
            <?= $client_info->fullName ?>
        </p>
            
            <?= $form->field($add_rent, 'account_id')->dropDownList($list_account, [
                    'value' => $account_choosing->account_id,
                    'id' => '_list-account',
                    'class' => 'form-control',
                    'data-client' => $client_info->clients_id]) ?>
        <div class="control-block">
            <?php if ($user_info->status == 1) : ?>
                <?= Html::button('Заблокировать', [
                        'class' => 'btn-block-user block_user',
                        'data-user' => $user_info->user_id,
                        'data-status' => 2]) 
                ?>
            <?php elseif ($user_info->status == 2)  : ?>
                <?= Html::button('Разблокировать', [
                        'class' => 'btn-unblock-user block_user',
                        'data-user' => $user_info->user_id,
                        'data-status' => 1]) 
                ?>
            <?php endif; ?>
            <?= Html::button('Удалить', [
                        'class' => 'btn-delete-user',
                        'data-user' => $user_info->user_id]) 
            ?>
            
        </div>
    </div>
</div>

<div class="profile-menu profile-sub_menu">
    <ul class="profile-sub_menг_ul">
        <li class="active"><a href="<?= Url::to(['clients/view-client', 'client_id' => $client_info->clients_id, 'account_number' => $account_choosing->account_number]) ?>">Профиль</a></li>
        <li><a href="#">Квитанция ЖКУ</a></li>
        <li><a href="#">Платежи</a></li>
        <li><a href="#">Показания приборов учета</a></li>
        <li><a href="#">Общая информация по лицевому счету</a></li>
    </ul>
</div>
