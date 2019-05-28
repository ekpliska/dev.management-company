<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use app\helpers\FormatHelpers;
    
?>


<table class="table table-striped table-account">
    <tbody>
        <tr>
            <td>
                <p class="title">Номер лицевого счета</p>
                <?= HtmlPurifier::process($account_info['account_number']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Организация</p>
                <?= HtmlPurifier::process($account_info['organizations_name']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Арендатор</p>
                <?= !empty($account_info['personal_rent_id']) ? 
                        HtmlPurifier::process(FormatHelpers::formatFullUserName(
                                $account_info['rents_surname'],
                                $account_info['rents_name'],
                                $account_info['rents_second_name'], true)) : 'Арендатор отсутствует' ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Адрес</p>
                <?= HtmlPurifier::process(FormatHelpers::formatFullAdress(
                        $account_info['houses_gis_adress'],
                        $account_info['houses_street'],
                        $account_info['houses_number'],
                        false,
                        false,
                        $account_info['flats_number'])) ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Номер парадной</p>
                <?= HtmlPurifier::process($account_info['flats_porch']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Номер этажа</p>
                <?= HtmlPurifier::process($account_info['flats_floor']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <p class="title">Жилая площадь квартиры</p>
                <?= HtmlPurifier::process($account_info['flats_square']) ?>
            </td>
        </tr>
    </tbody>
</table>