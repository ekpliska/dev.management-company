<?php

    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;

/* 
 * Профиль собсвенника, раздел Приборы устройства
 */

$this->title = 'Собственники';
$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = ['label' => 'Собственники', 'url' => ['clients/index']];
$this->params['breadcrumbs'][] = $client_info->fullName . ' [' . $account_choosing->account_number . ']';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="profile-page">
        <?= $this->render('page-profile/header', [
                'user_info' => $user_info,
                'client_info' => $client_info,
                'list_account' => $list_account,
                'account_choosing' => $account_choosing,
        ]) ?>
        
    
        <div class="row">
            <table class="table table-striped table-account">
                <tbody>
                    <tr>
                        <td scope="row">Номер лицевого счета</td>
                        <td><?= $account_choosing['account_number'] ?></td>
                    </tr>
                    <tr>
                        <td>Организация</td>
                        <td><?= $account_choosing['organization']['organizations_name'] ?></td>
                    </tr>
                    <tr>
                        <td>Собственник</td>
                        <td><?= $account_choosing['client']->fullName ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Арендатор</td>
                        <td><?= isset($account_choosing['rent']) ? $account_choosing['rent']->fullName : 'Арендатор отсутствует.' ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Контактный телефон</td>
                        <td><?= $user_info->user_mobile ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Адрес</td>
                        <td>
                            <?= FormatHelpers::formatFullAdress(
                                    $account_choosing['flat']['house']['houses_gis_adress'], 
                                    $account_choosing['flat']['house']['houses_street'], 
                                    $account_choosing['flat']['house']['houses_number'], 
                                    false, false, 
                                    $account_choosing['flat']['flats_number']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Номер парадной</td>
                        <td><?= $account_choosing['flat']['flats_porch'] ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Номер этажа</td>
                        <td><?= $account_choosing['flat']['flats_floor'] ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Жилая площадь квартиры</td>
                        <td><?= $account_choosing['flat']['flats_square'] ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-left-tb">Баланс</td>
                        <td>
                            <span class="<?= $account_choosing['account_balance'] < 0 ? 'balance-debt' : 'balance' ?>">
                                <?= $account_choosing['account_balance'] ?><i class="fa fa-rub" aria-hidden="true"></i>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>    
        </div>
    </div>
</div>