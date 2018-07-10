<?php
    use yii\widgets\DetailView;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
/* 
 * Лицевой счет / Общая информация
 */
$this->title = 'Лицевой счет | Общая информация';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    <div class="col-md-6">
        Лицевой счет
        <?php
            // var_dump($number_account)
        ?>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-account-modal">Добавить лицевой счет</button>
    </div>
    <div class="col-md-6">
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Лицевой счет</strong>                    
                </div>
            </div>

            
            <div class="panel-body">
                <?php /* DetailView::widget([
                    'model' => $account,
                    'attributes' => [
                        'account_number',
                        [
                            'attribute' => 'Организация',
                            'value' => function ($data) {
                                return $data->organization->organizations_name;
                            },
                        ],
                        [
                            'attribute' => 'Собственник',
                            'value' => $account->client->fullName,
                        ],
                        [
                            'attribute' => 'Телефон',
                            'value' => $account->client->phone,
                        ],
                        [
                            'attribute' => 'Арендатор',
                            'value' => function ($data) {
                                return $data->client->is_rent ? $data->rent->fullName : 'Арендатор отсутствует';
                            },
                        ],
                        [
                            'attribute' => 'Адрес',
                            'value' => $account->house->adress,                            
                        ],
                        [
                            'attribute' => 'Парадная',
                            'value' => $account->house->porch,                            
                        ],
                        [
                            'attribute' => 'Этаж',
                            'value' => $account->house->floor,                            
                        ],
                        [
                            'attribute' => 'Количество комнат',
                            'value' => $account->house->rooms,                            
                        ],
                        [
                            'attribute' => 'Жилая площадь (кв.м.)',
                            'value' => $account->house->square,                            
                        ],                        
                    ],
                ]) */ ?>                
            </div>
        </div>
    </div>    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Информаци об оплате</strong>                    
                </div>
            </div>            
            <div class="panel-body">
                test
            </div>
        </div>
    </div>    
</div>


<div id="add-account-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавить лицевой счет</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <?php 
                            $form = ActiveForm::begin([
                                'id' => 'add-account',
                            ])
                        ?>

                            <div class="col-md-6">
                                <?= $form->field($model, 'account_number')->input('text', ['placeHolder' => $model->getAttributeLabel('account_number')])->label() ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'account_last_sum')->input('text', ['placeHolder' => $model->getAttributeLabel('account_last_sum')])->label() ?>
                            </div>

                            <div class="col-md-12">
                                <?= $form->field($model, 'account_organization_id')
                                    ->dropDownList(ArrayHelper::map(\app\models\Organizations::find()->orderBy('organizations_name asc')->all(),
                                            'organizations_id', 'organizations_name'),
                                            ['prompt' => 'Выберите организацию из списка'])
                                ?>
                            </div>
                        
                            <div class="col-md-12">
                                <?= $form->field($model, 'account_client_surname')
                                    ->input('text', [
                                        'placeHolder' => $model->getAttributeLabel('account_client_surname'),
                                        'value' => $account->client->clients_surname,
                                    ])
                                    ->label() ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $form->field($model, 'account_client_name')
                                    ->input('text', [
                                        'placeHolder' => $model->getAttributeLabel('account_client_name'),
                                        'value' => $account->client->clients_name,
                                    ])
                                    ->label() ?>
                            </div>
                        
                            <div class="col-md-6">
                                <?= $form->field($model, 'account_client_secondname')
                                    ->input('text', [
                                        'placeHolder' => $model->getAttributeLabel('account_client_secondname'), 
                                        'value' => $account->client->clients_second_name,
                                    ])
                                    ->label() ?>
                            </div>
                        
                            <div class="col-md-12">
                                <?php // var_dump($all_rent) ?>
                                <?= $form->field($model, 'account_rent')->input('text', ['placeHolder' => $model->getAttributeLabel('account_rent')])->label() ?>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-danger']) ?>
                <?= Html::submitButton('Отмена', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
