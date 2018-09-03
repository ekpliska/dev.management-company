<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use app\helpers\FormatHelpers;
    
?>

<div class="account-item" id="account-info">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Лицевой счет</td>
                <td><?= Html::encode($account_info['account_number']) ?></td>
            </tr>
            <tr>
                <td>Организация</td>
                <td><?= HtmlPurifier::process($account_info['organizations_name']) ?></td>
            </tr>
            <tr>
                <td>Собственник</td>
                <td><?= HtmlPurifier::process(
                        FormatHelpers::formatFullUserName(
                                $account_info['clients_surname'], 
                                $account_info['clients_name'], 
                                $account_info['clients_second_name']
                        )) 
                    ?>
                </td>
            </tr>        
            <tr>
                <td>Телефон</td>
                <td><?= HtmlPurifier::process($account_info['clients_mobile']) ?></td>
            </tr>        
            <tr>
                <td>Арендатор</td>
                <td>
                    <?php $account_info['rents_id'] ?
                            HtmlPurifier::process(FormatHelpers::formatFullUserName(
                                    $account_info['rents_surname'], 
                                    $account_info['rents_name'], 
                                    $account_info['rents_second_name']
                            )) : '' ?>
                </td>
            </tr>        
            <tr>
                <td>Адрес</td>
                <td><?= HtmlPurifier::process(
                        $account_info['houses_town'] . 
                        $account_info['houses_street'] .
                        $account_info['houses_number_house'] .
                        $account_info['houses_flat']) 
                    ?>
                </td>
            </tr>        
            <tr>
                <td>Парадная</td>
                <td><?= HtmlPurifier::process($account_info['houses_porch']) ?></td>
            </tr>
            <tr>
                <td>Этаж</td>
                <td><?= HtmlPurifier::process($account_info['houses_floor']) ?></td>
            </tr>        
            <tr>
                <td>Количество комнат</td>
                <td><?= HtmlPurifier::process($account_info['houses_rooms']) ?></td>
            </tr>        
            <tr>
                <td>Жилая площадь (кв. метр)</td>
                <td><?= HtmlPurifier::process($account_info['houses_square']) ?></td>
            </tr>        

        </tbody>
    </table>
</div>