<?php
    
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\widgets\MaskedInput;
    use yii\helpers\Url;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'Мои заявки';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-account-modal">Добавить заявку</button>
        <h4>Статусы заявок</h4>
        <div class="list-group">
            <a href="#" class="list-group-item">Все</a>
            <a href="#" class="list-group-item">Новые</a>
            <a href="#" class="list-group-item">В работе</a>
            <a href="#" class="list-group-item">Исполненные</a>
            <a href="#" class="list-group-item">На уточнении</a>
            <a href="#" class="list-group-item">Закрытые</a>
        </div>
    </div>
    <div class="col-md-9">
        
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-info" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('success', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                    
        </div>
    <?php endif; ?>
    
    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert alert-error" role="alert">
            <strong>
                <?= Yii::$app->session->getFlash('error', false); ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>                
        </div>
    <?php endif; ?>         
        
        <?= GridView::widget([
            'dataProvider' => $all_requests,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                'requests_ident',
                [
                    'attribute' => 'requests_type_id',
                    'value' => function ($data) {
                        return $data->getNameRequest();
                    },
                ],
                'requests_comment',
                'requests_specialist_id',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d.m.Y H:m:i'],
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:d.m.Y H:m:i'],
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($data) {
                        return $data->getStatusName();
                    }
                    ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view-request}',
                    'buttons' => [
                        'view-request' => function ($url, $data) {
                            $url = ['request_numder' => $data->requests_ident];
                            return '<a href='. Url::to(['requests/view-request', 'request_numder' => $data->requests_ident]) .' class="btn btn-info">Подробнее</a>';
                        },
                    ],
                ],
                
            ],
            ]); ?>
    </div>
</div>

<div id="add-account-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Создать заявку</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <?php
                        $form = ActiveForm::begin([
                            'id' => 'add-request',
                        ])
                    ?>
                        <?= $form->field($model, 'request_type')
                                ->dropDownList(ArrayHelper::map(\app\models\TypeRequests::find()->orderBy('type_requests_name asc')->all(),
                                        'type_requests_id', 'type_requests_name'),
                                        ['prompt' => 'Выберите вид заявки из списка'])
                        ?>
                    
                        <?= $form->field($model, 'request_phone')
                                ->widget(MaskedInput::className(), [
                                    'mask' => '+7 (999) 999-99-99',
                                ])
                                ->input('text', ['placeHolder' => $model->getAttributeLabel('request_phone')])->label() ?>
                    
                        <?= $form->field($model, 'request_comment')
                                ->textarea(['rows' => 10]) ?>
                </div>
            </div>
            <div class="modal-footer">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>