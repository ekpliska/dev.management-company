<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use app\helpers\FormatHelpers;
    
?>


<table class="table table-striped table-account">
    <thead>
        <tr>
            <td colspan="2">Общая информация</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td scope="row">Номер лицевого счета</td>
            <td>
                <?= HtmlPurifier::process($account_info['account_number']) ?>
            </td>
        </tr>
        <tr>
            <td>Организация</td>
            <td>
                <?= HtmlPurifier::process($account_info['organizations_name']) ?>
            </td>
        </tr>
        <tr>
            <td>Собственник</td>
            <td>
                <?= HtmlPurifier::process(FormatHelpers::formatFullUserName(
                        $account_info['clients_surname'],
                        $account_info['clients_name'],
                        $account_info['clients_second_name'], true))
                    ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Арендатор</td>
            <td class="text-right-tb">
                <?= !empty($account_info['personal_rent_id']) ? 
                        HtmlPurifier::process(FormatHelpers::formatFullUserName(
                                $account_info['rents_surname'],
                                $account_info['rents_name'],
                                $account_info['rents_second_name'], true)) : 'Арендатор отсутствует' ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Контактный телефон</td>
            <td class="text-right-tb">
                <?= Yii::$app->userProfile->mobile ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Адрес</td>
            <td class="text-right-tb">
                <?= HtmlPurifier::process(FormatHelpers::formatFullAdress(
                        $account_info['houses_gis_adress'],
                        $account_info['houses_number'],
                        false,
                        false,
                        $account_info['flats_number'])) ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Номер парадной</td>
            <td class="text-right-tb">
                <?= HtmlPurifier::process($account_info['flats_porch']) ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Номер этажа</td>
            <td class="text-right-tb">
                <?= HtmlPurifier::process($account_info['flats_floor']) ?>
            </td>
        </tr>
        <tr>
            <td scope="row" class="text-left-tb">Жилая площадь квартиры</td>
            <td class="text-right-tb">
                <?= HtmlPurifier::process($account_info['flats_square']) ?>
            </td>
        </tr>
    </tbody>
</table>