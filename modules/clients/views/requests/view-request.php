<?php
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Детали заявки
 */
$this->title = 'Детали заявки';

$user = Yii::$app->user->identity->user_id; 
$username = Yii::$app->user->identity->user_login;
// $account = Yii::$app->user->identity->user_account_id;
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-7">
        <div class="panel panel-default">
        <div class="panel-heading">Детали заявки № <?= $request_info->requests_ident ?></div>
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
                        <td>Дата закрытия заявки</td>
                        <td><?= $request_info->status == 4 ? Yii::$app->formatter->asDatetime($request_info->updated_at, "php:d.m.Y H:i:s") : '' ?></td>
                    </tr>
                    <tr>
                        <td>Заявка принята</td>
                        <td>
                            <?= $request_info->is_accept ? '<span style="color: #ab1a1a" class="glyphicon glyphicon-ok"></span>' : '<span style="color: #c1c1c1;" class="glyphicon glyphicon-ok"></span>' ?>
                        </td>
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
                    <tr>
                        <td>Прикрепленные файлы</td>
                        <td>
                            <?php if (isset($all_images)) : ?>
                                <?php foreach ($all_images as $image) : ?>
                                    <?= FormatHelpers::formatUrlFileRequest($image->getImagePath($image->filePath)) ?>
                                    <br />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>            
        </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <?= $this->render('form/_comment', [
            'model' => $comments, 
            'comments_find' => $comments_find, 
            'request_id' => $request_info->id
        ]); ?>
    </div>
    
</div>
