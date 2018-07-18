<?php
    namespace app\modules\clients\controllers;
    use Yii;
    use yii\web\Controller;
    use app\models\PersonalAccount;
    use app\modules\clients\models\AddPersonalAccount;
    use app\models\User;
    use app\modules\clients\models\FilterForm;
    use yii\data\ActiveDataProvider;

/**
 * Контроллер по работе с разделом "Лицевой счет"
 */
class PersonalAccountController extends Controller {

    public function actionIndex($user, $username) {
        
        $user_info = User::findByUser($user, $username);
        
        if ($user_info === null) {
            throw new NotFoundHttpException('Вы обратились к несуществующей странице');
        }
        
        // Загружаем форму для добавления лицевого счета
        $add_account = new AddPersonalAccount();
        
        // Загружаем в провайдер данных информацию об основном лицевом счете (указанный при регистрации)
        $dataProvider = new ActiveDataProvider([
            'query' => PersonalAccount::findByUserID($user_info->user_id),
        ]);
        
        $dataProvider->pagination = false;        

        // Форма для фильтрации лицевых счетов
        $_filter = new FilterForm();
        // Получить список всех лицевых счетов пользователя        
        $account_all = PersonalAccount::findByClient($user_info->user_client_id);
        
        return $this->render('index', [
            'add_account' => $add_account, 
            'account_all' => $account_all,
            '_filter' => $_filter,
            'dataProvider' => $dataProvider,
        ]);
        
    }

    /*
     * Метод для фильтра лицевых счетов
     */
    public function actionList($id) {
        if (isset($_POST['id'])) {
            return $this->refresh();
        } else {
            if (Yii::$app->request->isAjax) {
                $account_number = PersonalAccount::findOne(['account_id' => $id]);
                return $this->renderAjax('list', ['model' => $account_number]);
            }
        }
        
//        if (isset($_POST['id']))
//        {
//            return $this->render('index');
//        }else{
//            $searchModel = new LocationSearch();
//            $model = new Location();
//            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//            return $this->render('list',[
//                'model' => $model,
//                'searchModel' => $searchModel,
//                'dataProvider' => $dataProvider,
//            ]);
//        }
    }    
    
}
