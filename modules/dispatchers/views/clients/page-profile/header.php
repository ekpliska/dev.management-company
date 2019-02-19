<?php

    use yii\helpers\Html;
    use app\modules\dispatchers\widgets\SubMenu;

/* 
 * Шапка профиля собсвенника
 * Подменю
 */
?>
<div class="profile-bg text-center">
    <div class="col-md-3 col-xs-6 face-user">
        <?= Html::img($user_info->photo, [
                'class' => 'img-rounded',
                'alt' => 'user-photo',
        ]) ?>
    </div>
    <div class="col-md-8 col-xs-6 text-left user-info-block">
        <p class="user-info-block_name">
            <?= $client_info->fullName ?>
            <?php if ($account_choosing->account_balance < 0) : ?>
                <span class="debt-client">&ndash;&#8381;</span>
            <?php endif; ?>                
        </p>

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