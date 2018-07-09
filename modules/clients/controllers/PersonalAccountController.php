<?php
    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\Rents;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends Controller {

    public function actionIndex($number) {
        
        $model = new AddPersonalAccount();
        
        $account = PersonalAccount::findByClient($number);
        
        // $all_rent = Rents::findByRent($account->personal_clients_id);
        // $all_account = Clients::findOne(['clients_id' => $number])->personalAccount;
        // $number_account = ArrayHelper::map($all_account, 'account_id', 'account_number');
        
        
        return $this->render('index', ['account' => $account, 'model' => $model, 'all_rent' => $all_rent]);
        
    }
}
