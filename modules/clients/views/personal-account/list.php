<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    
?>

<div class="account-item" id="account-info">
 <table class="table table-bordered">
    <tbody>
        <tr>
            <td>Лицевой счет</td>
            <td><?= Html::encode($model->account_number) ?></td>
        </tr>
        <tr>
            <td>Организация</td>
            <td><?= HtmlPurifier::process($model->organization->organizations_name) ?></td>
        </tr>
        <tr>
            <td>Собственник</td>
            <td><?= HtmlPurifier::process($model->client->fullName) ?></td>
        </tr>        
        <tr>
            <td>Телефон</td>
            <td><?= HtmlPurifier::process($model->client->phone) ?></td>
        </tr>        
        <tr>
            <td>Арендатор</td>
            <td><?= HtmlPurifier::process($model->personal_rent_id ? $model->client->rent->fullName : 'Арендатор отсутствует') ?></td>
        </tr>        
        <tr>
            <td>Адрес</td>
            <td><?= HtmlPurifier::process($model->client->house->adress) ?></td>
        </tr>        
        <tr>
            <td>Парадная</td>
            <td><?= HtmlPurifier::process($model->client->house->porch) ?></td>
        </tr>
        <tr>
            <td>Этаж</td>
            <td><?= HtmlPurifier::process($model->client->house->floor) ?></td>
        </tr>        
        <tr>
            <td>Количество комнат</td>
            <td><?= HtmlPurifier::process($model->client->house->rooms) ?></td>
        </tr>        
        <tr>
            <td>Жилая площадь (кв. метр)</td>
            <td><?= HtmlPurifier::process($model->client->house->square) ?></td>
        </tr>        
        
    </tbody>
  </table>
</div>