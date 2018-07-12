<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\widgets\Pjax;
    use yii\widgets\ListView;
    use yii\helpers\Url;
/* 
 * Лицевой счет / Общая информация
 */
$this->title = 'Общая информация';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <div class="col-md-6">
        <?php 
            $form_filter = ActiveForm::begin([
                'id' => 'filter-form-account',
                'options' => [
                    'class' => 'form-inline',
                    'data-pjax' => true,
                ],
            ]); 
        ?>
        
            <?= $form_filter->field($_filter, '_value')
                    ->dropDownList($account_all, [
                        'onchange' => '$.pjax.reload({container: "#pjax-list-account", url: "'.Url::to(['personal-account/list']).'", data: {id: $(this).val()}});',
                    ]) ?>
        
        <?php ActiveForm::end(); ?> 
    </div>
    
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-account-modal">Добавить лицевой счет</button>
    </div>
    <div class="clearfix"></div>    
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Иформация по лицевому счету</strong></div>
            <div class="panel-body">
                <?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax-list-account']); ?>
            
                    <?= ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => 'list',
                            'layout' => "{pager}\n{summary}\n{items}\n{pager}",
                        ]) ?>
            
                <?php Pjax::end(); ?>
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
                                <?= $form->field($add_account, 'account_number')->input('text', ['placeHolder' => $add_account->getAttributeLabel('account_number')])->label() ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($add_account, 'account_last_sum')->input('text', ['placeHolder' => $add_account->getAttributeLabel('account_last_sum')])->label() ?>
                            </div>

                            <div class="col-md-12">
                                <?= $form->field($add_account, 'account_organization_id')
                                    ->dropDownList(ArrayHelper::map(\app\models\Organizations::find()->orderBy('organizations_name asc')->all(),
                                            'organizations_id', 'organizations_name'),
                                            ['prompt' => 'Выберите организацию из списка'])
                                ?>
                            </div>
                        
                            <div class="col-md-12">
                                <?= $form->field($add_account, 'account_client_surname')
                                    ->input('text', [
                                        'placeHolder' => $add_account->getAttributeLabel('account_client_surname'),
                                        'value' => $account->client->clients_surname,
                                    ])
                                    ->label() ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?= $form->field($add_account, 'account_client_name')
                                    ->input('text', [
                                        'placeHolder' => $add_account->getAttributeLabel('account_client_name'),
                                        'value' => $account->client->clients_name,
                                    ])
                                    ->label() ?>
                            </div>
                        
                            <div class="col-md-6">
                                <?= $form->field($add_account, 'account_client_secondname')
                                    ->input('text', [
                                        'placeHolder' => $add_account->getAttributeLabel('account_client_secondname'), 
                                        'value' => $account->client->clients_second_name,
                                    ])
                                    ->label() ?>
                            </div>
                        
                            <div class="col-md-12">
                                <?php // var_dump($all_rent) ?>
                                <?= $form->field($add_account, 'account_rent')->input('text', ['placeHolder' => $add_account->getAttributeLabel('account_rent')])->label() ?>
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
