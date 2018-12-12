<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Подменю профиль собственника
 */
$action = Yii::$app->controller->action->id;
?>
<?php if (isset($params)) : ?>
    <div class="profile-menu profile-sub_menu">
        <ul class="profile-sub_menг_ul">
            <?php foreach ($params as $key => $item) : ?>
                <li class="<?= $action == $key ? 'active' : '' ?>">
                    <a href="<?= Url::to([$item['link'], 'client_id' => $client_id, 'account_number' => $account_number]) ?>">
                        <?= $item['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>