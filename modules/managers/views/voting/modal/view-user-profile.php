<?php

    use app\helpers\FormatHelpers;
    use yii\helpers\Html;

/* 
 * Просмотр профиля пользователя в модальном окне на странице "Редактирование голосования"
 */
?>
<?php if (isset($user_info)) : ?>
<div class="col-md-4 profile_photo">
    <?php $user_photo = $user_info['photo'] ? $user_info['photo'] : "images/no-avatar.jpg" ?>
    <?= Html::img("@web/{$user_photo}", ['alt' => 'user-name', 'class' => 'profile_photo__image']) ?>
</div>
<div class="col-md-8 profile_info">
    <h4>
        <?= $user_info['name'] . ' ' . $user_info['second_name'] . ' ' . $user_info['surname'] ?>
    </h4>
    <span class="user-role-name">Собственник</span>
    <span class="last-login"> Последний раз был: <?= FormatHelpers::formatDate($user_info['last_login'], false, 0, false) ?></span>
    <hr>
    <table class="table user-info">
        <tbody>
            <tr>
                <td><span class="title">Телефон</span></td>
                <td><?= $user_info['mobile'] ?></td>
            </tr>
            <tr>
                <td><span class="title">E-mail</span></td>
                <td><?= $user_info['email'] ?></td>
            </tr>
            <tr>
                <td><span class="title">Адрес</span></td>
                <td><?= $user_info['full_adress'] . ', д. ' . $user_info['houses_number'] . ', кв. ' . $user_info['flat'] ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <?= Html::button('Закрыть', [
            'data-dismiss' => 'modal', 
            'class' => 'btn red-outline-btn',
    ]) ?>
</div>
<?php endif ;?>