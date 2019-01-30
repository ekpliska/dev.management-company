<?php

    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\models\RegistrationInVoting;

/* 
 * Вид отрисовки списка всех созданных голосований
 */
?>
<?php if (isset($view_all_voting) && (count($view_all_voting) > 0)) : ?>
    <?php foreach ($view_all_voting as $voting) : ?>
    <div class="vote-card">
        <div class="col-md-3 vote-card__image">
            <?= Html::img("@web{$voting['voting_image']}", [
                    'alt' => $voting['voting_title']]) ?>
        </div>
        <div class="col-md-2 vote-card__info">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <span class="status-vote-<?= $voting['status'] ?>"><?= FormatHelpers::statusNameVoting($voting['status']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="title">Начало голосования</span>
                            <p><?= FormatHelpers::formatDate($voting['voting_date_start'], true, 1, false) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="title">Окончание голосования</span>
                            <p><?= FormatHelpers::formatDate($voting['voting_date_end'], true, 1, false) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="title">Проголосовало</span>
                            <?php /* Определяем количество участников, которые закончили голосование */
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
                    <tr>
                        <td><?= FormatFullNameUser::nameEmployeeByUserID($voting['voting_user_id']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-7 vote-card__content">
            <h1 class="vote-card__content_title">
                <?= Html::a($voting['voting_title'] . " <span>ID{$voting['voting_id']}</span>", ['voting/view', 'voting' => $voting['voting_id']]) ?>
                <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'class' => 'pull-right btn-delete-question',
                        'data-toggle' => 'modal',
                        'data-target' => '#delete_voting_manager',
                        'data-voting' => $voting['voting_id']]) ?>
            </h1>
<?php /*            <div class="col-md-12 vote-card__content_author">
                <span><i class="glyphicon glyphicon-user"></i> <?= FormatFullNameUser::nameEmployeeByUserID($voting['voting_user_id']) ?></span>
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
                    <?php Определяем количество участников, которые закончили голосование
                        $count = 0;
                        foreach ($voting['registration'] as $participant) :
                            if ($participant['finished'] == RegistrationInVoting::STATUS_FINISH_YES) :
                                $count++;
                            endif; 
                        endforeach; 
                    ?>
                    <span class="title">Проголосовало: </span>
                    <span class="span-count"><?= $count ?></span>
                </div>
            </div>
 */ ?>
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
<?php else: ?>
<div class="notice info">
    <p>По указанному адресу функция голосования не создавалась.</p>
</div>
<?php endif; ?>