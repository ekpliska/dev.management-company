<?php

    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/* 
 * Вид отрисовки списка всех созданных голосований
 */
?>
<?php foreach ($view_all_voting as $voting) : ?>
<div class="vote-card">
    <div class="vote-card__image">
        <?= Html::img('@web/' . $voting['voting_image'], [
                'alt' => $voting['voting_title']]) ?>        
    </div>
    <div class="vote-card__content">
        <h1 class="vote-card__content_title">
            <?= Html::a($voting['voting_title'] . " <span>ID{$voting['voting_id']}</span>", ['voting/view', 'voting' => $voting['voting_id']]) ?>
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                    'class' => 'btn btn-sm pull-right btn-delete-question',
                    'data-toggle' => 'modal',
                    'data-target' => '#delete_voting_manager',
                    'data-voting' => $voting['voting_id']]) ?>
        </h1>
        <div class="col-md-12 vote-card__content_author">
            <span><i class="glyphicon glyphicon-user"></i> <?= FormatFullNameUser::nameEmployerByUserID($voting['voting_user_id']) ?></span>
        </div>
        <div class="vote-card__content_info">
            <div class="col-md-1 col-xs-6">
                <span class="status-vote-<?= $voting['status'] ?>"><?= FormatHelpers::statusNameVoting($voting['status']) ?>
            </div>
            <div class="col-md-4 col-xs-6">
                <span class="title">Начало голосования: </span>
                <?= FormatHelpers::formatDate($voting['voting_date_start'], true, 1, false) ?>
            </div>
            <div class="col-md-4 col-xs-6">
                <span class="title">Завершение голосование: </span>
                <?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1, false) ?>
            </div>
            <div class="col-md-3 col-xs-6">
                <span class="title">Проголосовало: </span>
                #TODO
            </div>
        </div>
        <div class="col-md-12 vote-card__content_text">
            <?= HtmlPurifier::process(strip_tags($voting['voting_text'])) ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= LinkPager::widget([
        'pagination' => $pages
    ]);
?>

<?php /*
<?php foreach ($view_all_voting as $voting) : ?>
    <div class="col-md-12">
        <div class="col-md-2">
            <?= Html::img('@web/' . $voting['voting_image'], [
                    'alt' => $voting['voting_title'], 
                    'class' => 'img-thumbnail', 
                    'width' => '150', 
                    'height' => '150']) ?>
            
            <?= Html::button('Удалить', [
                    'class' => 'btn btn-danger btn-sm',
                    'data-toggle' => 'modal',
                    'data-target' => '#delete_voting_manager',
                    'data-voting' => $voting['voting_id']]) ?>
        </div>
        <div class="col-md-10">
            <p>
                <?= Html::a($voting['voting_title'], ['voting/view', 'voting' => $voting['voting_id']]) ?>
                <span class="label label-primary">ID <?= $voting['voting_id'] ?></span>
            </p>
            <p>
                <span class="label label-success"><?= FormatHelpers::statusNameVoting($voting['status']) ?></span>
                <b>Дата начала голосования:</b> 
                <b>Дата окончания голосования:</b> <?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1) ?>
                <b>Проголосовало:</b> #COUNT
            </p>
            <hr />
            <?= HtmlPurifier::process(strip_tags($voting['voting_text'])) ?>
        </div>
    </div>

    <div class="col-md-12 text-right" style="padding: 5px;">
        <p class="small">
            Автор: <?= FormatFullNameUser::nameEmployerByUserID($voting['voting_user_id']) ?>
            <b>Дата создания:</b> <?= FormatHelpers::formatDate($voting['created_at'], false) ?>
            <b>Последний раз редактировалось: </b> <?= FormatHelpers::formatDate($voting['updated_at'], false) ?>
            <?= Html::a('Ознакомиться', ['voting/view', 'voting' => $voting['voting_id']]) ?>
        </p>
    </div>
    <div class="clearfix"></div>
    <hr />
<?php endforeach; ?>

    
<?= LinkPager::widget([
        'pagination' => $pages
    ]);
?>
 * 
 */ ?>