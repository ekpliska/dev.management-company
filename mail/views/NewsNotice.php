<?php

    use yii\helpers\Html;

/* 
 * Уведомление о новой новости
 */
    
$post_path = $params['post_slug'] ? 
        Yii::$app->urlManager->createAbsoluteUrl(['/']) . "/news/{$params['post_slug']}" :
        Yii::$app->urlManager->createAbsoluteUrl(['/']) . "/voting/{$params['id_post']}";
?>

<table border="0" cellpadding="10" cellspacing="10" width="100%" align="center" valign="middle" style="font-family: sans-serif; font-size: 14px;">
    <thead>
        <tr>
            <th colspan="2" style="border-bottom: 1px solid #dddddd;">
                <img src="<?= Yii::$app->urlManager->createAbsoluteUrl(['images/main/elsa_logo.png']) ?>" alt="ELSA LOGO" width="170">
                <p style="font-family: sans-serif; font-size: 12px; letter-spacing: 2; color: #331fb6;">
                    ELECTRONIC SMART ASSISTEN
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                <?= Yii::$app->formatter->asDate(time(), 'd MMMM, YYYY') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Здравствуйте, вы подписанны на рассылку email-уведомлений на портале 
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">ELSA</a>. 
                На нашем портале появилась публикация, которая, возможно вас заинтересует.
            </td>
        </tr>
        <tr>
            <td width="20%">
                <img src="<?= Yii::$app->urlManager->createAbsoluteUrl($params['post_image']) ?>" alt="Image publish" width="240" height="170" style="display:block;">
            </td>
            <td valign="top">
                <h3 style="color: #454545;"><?= Html::encode($params['post_title']) ?></h3>
                <br>
                <?= Html::encode($params['post_text']) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <a href="<?= $post_path ?>" class="view-news" target="_blank">
                    Ознакомиться
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px; color: #777777;" bgcolor="#dddddd">
                Если вы больше не хотите получать новостную рассылку с портала <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">ELSA</a>, вы 
                можете воспользоваться вариантами указанными ниже:
                <ol>
                    <li>
                        Вы можете отписаться от рассылки в личном кабинете, раздел Настройки профиля
                    </li>
                    <li>
                        Перейдя по ссылке 
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) . 'unsubscribe?qs=' . base64_encode($email) ?>" class="unsubcriber">
                            Отписаться от рассылки
                        </a>
                    </li>
                </ol>
            </td>
        </tr>
    </tbody>
</table>