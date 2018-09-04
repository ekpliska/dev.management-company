<?php

    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\RatingRequest;

/* 
 * Детали заявки
 */
$this->title = 'Детали заявки';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-7">
        <div class="panel panel-default">
        <div class="panel-heading">Детали заявки № <?= $request_info['requests_ident'] ?></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Вид заявки</td>
                        <td><?= $request_info['type_requests_name'] ?></td>
                    </tr>
                    <tr>
                        <td>Идентификатор</td>
                        <td><?= $request_info['requests_ident'] ?></td>
                    </tr>
                    <tr>
                        <td>Адрес</td>
                        <td><?= FormatHelpers::formatFullAdress(
                                $request_info['houses_town'], 
                                $request_info['houses_street'], 
                                $request_info['houses_number_house'], 
                                $request_info['houses_flat']) 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Описание</td>
                        <td>
                            <div class="cutstring" data-display="none" data-max-length="70">
                                <?= $request_info['requests_comment'] ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Контактный телефон</td>
                        <td><?= $request_info['requests_phone'] ?></td>
                    </tr>
                    <tr>
                        <td>Статус заявки</td>
                        <td><?= FormatHelpers::statusName($request_info['status']) ?></td>
                    </tr>
                    <tr>
                        <td>Дата закрытия заявки</td>
                        <td><?= $request_info['status'] == 4 ? Yii::$app->formatter->asDatetime($request_info['updated_at'], "php:d.m.Y H:i:s") : '' ?></td>
                    </tr>
                    <tr>
                        <td>Заявка принята</td>
                        <td>
                            <?= $request_info['is_accept'] ? '<span style="color: #5cb85c" class="glyphicon glyphicon-ok"></span>' : '<span style="color: #c1c1c1;" class="glyphicon glyphicon-ok"></span>' ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оценка</td>
                        <td>
                            <?= RatingRequest::widget([
                                '_status' => $request_info['status'], 
                                '_request_id' => $request_info['requests_id'],
                                '_score' => $request_info['requests_grade']]) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Время создания</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info['created_at'], 'php:d.m.Y H:i:s') ?></td>
                    </tr>
                    <tr>
                        <td>Время изменения</td>
                        <td><?= Yii::$app->formatter->asDatetime($request_info['updated_at'], 'php:d.m.Y H:i:s') ?></td>
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
            'request_id' => $request_info['requests_id']
        ]); ?>
    </div>
    
</div>
