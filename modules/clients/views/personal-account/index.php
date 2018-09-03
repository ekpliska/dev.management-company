<?php
    use yii\helpers\Html;
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
        <?= Html::dropDownList('_list-account-all', $this->context->_choosing, $account_all, [
            'class' => 'form-control _list-account-all',
            'data-client' => $account_info['clients_id']]) ?>
        <br />
    </div>
    
    <div class="col-md-6 text-right">
        <?= Html::a('Добавить лицевой счет', Url::to(['personal-account/show-add-form']), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>    
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Информация по лицевому счету</strong></div>
            <div class="panel-body">
                <?= $this->render('_data-filter/list', ['account_info' => $account_info]); ?>
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
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Китанция № 00/0000</td>
                            <td>Сумма: 0 000,00</td>
                            <td>
                                <?= Html::button('Оплатить', ['class' => 'btn btn-success']) ?>
                            </td>
                        </tr>          
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>