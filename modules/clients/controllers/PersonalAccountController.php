<?php
    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use yii\helpers\ArrayHelper;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends Controller {

    public function actionIndex($number) {
        
        $account = PersonalAccount::findByClient($number);
        $all_account = Clients::findOne(['clients_id' => $number])->personalAccount;
        $number_account = ArrayHelper::map($all_account, 'account_id', 'account_number');
        return $this->render('index', ['account' => $account, 'number_account' => $number_account]);
        
    }
}
