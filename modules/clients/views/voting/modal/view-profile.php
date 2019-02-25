<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Рендер модального окна, профиль участника голсования
 */
?>
<?php if (!empty($user_info)) : ?>
<div class="card card-view-profile text-center">
    <?php $avatar = $user_info['user_photo'] ? $user_info['user_photo'] : "images/no-avatar.jpg" ?>
    <?= Html::img("@web/{$avatar}", ['alt' => 'user-name', 'class' => 'img-responsive img-circle']) ?>
    <h1>
        <?= $user_info['clients_surname'] . ' ' . $user_info['clients_name'] . ' ' . $user_info['clients_second_name'] ?>
    </h1>
    <p class="user-role-name">Собственник</p>
    <p class="title">
        Дата регистрации: <?= FormatHelpers::formatDate($user_info['created_at'], false, 0, false) ?>
    </p>
    <p class="title">
        Последний раз был: <?= FormatHelpers::formatDate($user_info['last_login'], false, 0, false) ?>
    </p>
</div>
<div class="modal-footer">
    <?= Html::button('Закрыть', [
            'data-dismiss' => 'modal', 
            'class' => 'btn-modal btn-modal-no',
    ]) ?>
                
</div>
<?php endif; ?>

