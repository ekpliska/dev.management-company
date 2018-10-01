<?php

    use app\modules\managers\widgets\AlertsShow;

/* 
 * Просмотр/редактирование новости
 */
$this->title = 'Новость: ' . $news->news_title;
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
</div>