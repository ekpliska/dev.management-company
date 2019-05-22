<?php

    use yii\helpers\Html;

/* 
 * Новые пользователи
 */
?>

<div class="__title">
    <h5>
        Новые пользователи
    </h5>
</div>
<div class="__content">
    <?php if (isset($user_lists) && count($user_lists) > 0) : ?>
    <?php foreach ($user_lists as $key => $user) : ?>
    <div class="new-user-block">
        <div class="new-user-block__photo">
            <?= Html::img($user->user->photo) ?>
        </div>
        <div class="new-user-block__info">
            
            <?= Html::a($user->surnameName, 
                    ['clients/view-client', 'client_id' => $user->clients_id, 'account_number' => $user->user->user_login], 
                    ['class' => 'new-user-block__user-name']) ?>
            
            <span>Лицевой счет: </span>
            <span class="new-user-block__info_item">
                <?= $user->user->user_login ?>
            </span>
        </div>
        
        <?= Html::a('Профиль', 
                ['clients/view-client', 'client_id' => $user->clients_id, 'account_number' => $user->user->user_login], 
                ['class' => 'new-user-block__profile']) ?>
        
    </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>
            Новых пользователей не найдено.
        </p>
    <?php endif; ?>

</div>