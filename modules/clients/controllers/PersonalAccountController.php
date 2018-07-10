<?php
    namespace app\modules\clients\controllers;
    use yii\web\Controller;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use yii\helpers\ArrayHelper;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\Rents;
    use app\models\User;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends Controller {

    public function actionIndex($user, $username, $account) {
        
        $user_info = User::findByUser($user, $username, $account);
        
        if ($user_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        $model = new AddPersonalAccount();
        
        $account = PersonalAccount::findByClient($number);
        
        return $this->render('index', ['account' => $account, 'model' => $model, 'all_rent' => $all_rent]);
        
    }
    
    public function actionViewRequest($request_numder) {
        
    }
    
}
