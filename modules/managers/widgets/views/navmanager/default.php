<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Выпадающее навигационное меню, Администраторы
 */
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>
<?php if (isset($menu)) : ?>
<div class="menu-wrap-manager">
    <div class="menu-sidebar-manager">
        <ul class="menu">
            <?php foreach ($menu as $key => $item) : ?>
            <li class="<?= ($controller == $key || $action == $key) ? 'active' : '' ?>">
                <a href="<?= Url::to([$item['link']]) ?>">
                    <?= $item['name'] ?>
                </a>
            </li>
            <?php endforeach; ?>
            <?php if (!empty(Yii::$app->userProfileCompany->employeeID)) : ?>
            <li class="menu-profile-item hidden-lg hidden-md">
                <?= Html::a('Профиль', ['employee-form/employee-profile', 'type' => 'administrator', 'employee_id' => Yii::$app->userProfileCompany->employeeID]) ?>
            </li>
            <?php endif; ?>
            <li class="menu-profile-item hidden-lg hidden-md">
                <?= Html::a('Выйти', ['/site/logout'], [
                        'data' => [
                            'method' => 'post'], 
                        'class' => 'float-right footer_link-logout']) ?>
            </li>
        </ul>
    </div>
</div>
<?php endif; ?>