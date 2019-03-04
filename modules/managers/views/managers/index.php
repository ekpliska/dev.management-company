<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/*
 * Администраторы
 */    
    
$this->title = Yii::$app->params['site-name-manager'] .  'Главная';
$this->params['breadcrumbs'][] = 'Главная';
?>

<div class="manager-main">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row manager-main__general">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 manager-main__general-left">
            <h4>
                Новости, Голосование
                <div class="dropdown settings-news">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                    <ul class="dropdown-menu">
                      <li><a href="<?= Url::to(['news/create']) ?>">Добавить публикацию</a></li>
                      <li><a href="<?= Url::to(['voting/create']) ?>">Добавить голосование</a></li>
                    </ul>
                </div>
            </h4>
            <?php if (!empty($news_content) && count($news_content) > 0) : ?>
            <div class="manager-main__general-left__content">
                <?php foreach ($news_content as $key => $post) : ?>
                    <div class="__content-news">
                        <?= Html::a($post['news_title'], ['/'], ['class' => 'title']) ?>
                        <p class="__content-date"><?= Yii::$app->formatter->asDate($post['created_at'], 'long') ?></p>
                        <div class="__content-news-item">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 __content-image">
                                <?= Html::img("/web/{$post['news_preview']}") ?>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 __content-text">
                                <?= FormatHelpers::shortTextNews($post['news_text'], 25) ?>
                            </div>                            
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <?= '=(' ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 manager-main__general-rigth">
            <div class="general-rigth__welcome">
                <h4>Добрый день: <?= Yii::$app->userProfileCompany->fullNameEmployee ?></h4>
                <h4>Сегодня: <?= Yii::$app->formatter->asDate(time(), 'full') ?></h4>
                <hr />
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 general-right__request">
                    <h4>
                        Заявки
                        <span class="pull-right"><?= count($request_list) ?></span>
                    </h4>
                    <?php if (!empty($request_list) && count($request_list) > 0) : ?>
                    <?php foreach ($request_list as $request) : ?>
                        <div class="general-right__request-body">
                            <h5>
                                <span class="requiest-id"><?= "ID {$request['number']}" ?></span>
                                <?= Html::a('Перейти', ['requests/view-request', 'request_number' => $request['number']], ['class' => 'pull-right']) ?>
                            </h5>
                            <div>
                                <p><span class="title">Вид заявки: </span><?= $request['type_requests'] ?></p>
                                <p><span class="title">Адрес:</span> <?= FormatHelpers::formatFullAdress($request['gis_adress'], $request['house']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 general-right__request">
                    <h4>
                        Платые услуги
                        <span class="pull-right"><?= count($paid_request_list) ?></span>
                    </h4>
                    <?php if (!empty($paid_request_list) && count($paid_request_list) > 0) : ?>
                    <?php foreach ($paid_request_list as $paid_request) : ?>
                        <div class="general-right__request-body">
                            <h5>
                                <span class="requiest-id"><?= "ID {$paid_request['number']}" ?></span>
                                <?= Html::a('Перейти', ['paid-requests/view-paid-request', 'request_number' => $paid_request['number']], ['class' => 'pull-right']) ?>
                            </h5>
                            <div>
                                <p><span class="title">Категория, услуга: </span><?= "{$paid_request['category']}, {$paid_request['service_name']}" ?></p>
                                <p><span class="title">Адрес:</span> <?= FormatHelpers::formatFullAdress($paid_request['gis_adress'], $paid_request['house']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
</div>