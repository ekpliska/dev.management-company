<?php
    use yii\helpers\Url;

/* 
 * Детали заявки
 */
$this->title = 'Детали заявки';

$user = Yii::$app->user->identity->user_id; 
$username = Yii::$app->user->identity->user_login;
$account = Yii::$app->user->identity->user_account_id;
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-7">
        <div class="panel panel-default">
        <div class="panel-heading">Детали заявки</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Вид заявки</td>
                        <td><?= $request_info->getNameRequest() ?></td>
                    </tr>
                    <tr>
                        <td>Идентификатор</td>
                        <td><?= $request_info->requests_ident ?></td>
                    </tr>
                    <tr>
                        <td>Адрес</td>
                        <td><?= $user_house->getAdress() ?></td>
                    </tr>
                    <tr>
                        <td>Описание</td>
                        <td><?= $request_info->requests_comment ?></td>
                    </tr>
                    <tr>
                        <td>Контактный телефон</td>
                        <td><?= $request_info->requests_phone ?></td>
                    </tr>
                    <tr>
                        <td>Статус заявки</td>
                        <td><?= $request_info->getStatusName() ?></td>
                    </tr>
                    <tr>
                        <td>Заявка принята</td>
                        <td><?= $request_info->is_accept ?></td>
                    </tr>
                    <tr>
                        <td>Оценка</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Время создания</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info->created_at, "php:d.m.Y H:i:s") ?></td>
                    </tr>
                    <tr>
                        <td>Время изменения</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info->updated_at, "php:d.m.Y H:i:s") ?></td>
                    </tr>
                </tbody>
            </table>            
        </div>
        </div>
    </div>
    
    <div class="col-md-5">
        chat
    </div>
    <div class="col-md-12 text-right">
        <a href="<?= Url::to(['requests/index', 'user' => $user, 'username' => $username, 'account' => $account]) ?>" class="btn btn-primary">Вернуться к списку заявок</a>
    </div>
    
</div>
