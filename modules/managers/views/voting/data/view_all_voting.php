<?php

    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\models\RegistrationInVoting;

/* 
 * Вид отрисовки списка всех созданных голосований
 */
?>
<?php if (isset($view_all_voting) && (count($view_all_voting) > 0)) : ?>
    <?php foreach ($view_all_voting as $voting) : ?>


<div class="col-lg-3 col-md-12 col-sm-12 col-md-12 vote-card__image">
    image
</div>
<div class="col-lg-9 col-md-12 col-sm-12 col-md-12 vote-card__content">
    content
</div>

<?php /*
    <div class="vote-card">
        <div class="col-lg-3 col-md-12 col-sm-12 col-md-12 vote-card__image">
            <?= Html::img("@web{$voting['voting_image']}", [
                    'alt' => $voting['voting_title']]) ?>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-md-12 vote-card__content">
            <h1 class="vote-card__content_title">
                <?= Html::a($voting['voting_title'] . " <span>ID{$voting['voting_id']}</span>", ['voting/view', 'voting' => $voting['voting_id']]) ?>
                <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'class' => 'pull-right btn-delete-question',
                        'data-toggle' => 'modal',
                        'data-target' => '#delete_voting_manager',
                        'data-voting' => $voting['voting_id']]) ?>
            </h1>
            
            <table class="table vote-table">
                <tbody>
                    <tr>
                        <td>
                            <span class="status-vote-<?= $voting['status'] ?>"><?= FormatHelpers::statusNameVoting($voting['status']) ?>
                        </td>
                        <td>
                            <span class="title">Начало голосования:</span>
                            <?= FormatHelpers::formatDate($voting['voting_date_start'], true, 1, false) ?>
                        </td>
                        <td>
                            <span class="title">Окончание голосования:</span>
                            <?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1, false) ?>
                        </td>
                        <td>
                            <span class="title">Проголосовало</span>
                            <?php 
                                //  Определяем количество участников, которые закончили голосование 
                                $count = 0;
                                foreach ($voting['registration'] as $participant) :
                                    if ($participant['finished'] == RegistrationInVoting::STATUS_FINISH_YES) :
                                        $count++;
                                    endif; 
                                endforeach; 
                            ?>
                            <span class="span-count"><?= $count ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>            
            <div class="col-md-12 vote-card__content_text">
                <?= HtmlPurifier::process(strip_tags($voting['voting_text'])) ?>
            </div>
        </div>
    </div>

 *  */ ?>
    <?php endforeach; ?>
    <?= LinkPager::widget([
            'pagination' => $pages
        ]);
    ?>
<?php else: ?>
<div class="notice info">
    <p>По указанному адресу функция голосования не создавалась.</p>
</div>
<?php endif; ?>