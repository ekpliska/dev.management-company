<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\SubMenu;

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

<?= SubMenu::widget(['view_name' => 'profile', 'client_id' => $client_info->clients_id, 'account_number' => $account_choosing->account_number]) ?>