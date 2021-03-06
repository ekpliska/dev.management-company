<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\SubMenu;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Шапка профиля собсвенника
 * Подменю
 */
?>
<div class="profile-bg text-center">
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 face-user">
        <?= Html::img($user_info->photo, [
                'class' => 'img-rounded',
                'alt' => 'user-photo',
        ]) ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left user-info-block">
        <p class="user-info-block_name">
            <?= $client_info->fullName ?>
            <?php if ($account_choosing->account_balance < 0) : ?>
                <span class="debt-client">&ndash;&#8381;</span>
            <?php endif; ?>                
        </p>

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
                        'data-target' => '#delete_clients_manager',
                        'data-toggle' => 'modal',
                        'data-user' => $client_info->clients_id]) 
            ?>
        </div>
  

        <?= Html::dropDownList('clients-account_list', $account_choosing->account_id, $list_account, [
                'placeHolder' => $account_choosing->account_number,
                'id' => 'select-dark',
                'class' => 'custom-select-dark',
                'data-client' => $client_info->clients_id,
                'data-url' => Yii::$app->controller->action->id,
        ]) ?>
        
    </div>
</div>

<?= SubMenu::widget(['view_name' => 'profile', 'client_id' => $client_info->clients_id, 'account_number' => $account_choosing->account_number]) ?>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_clients']) ?>