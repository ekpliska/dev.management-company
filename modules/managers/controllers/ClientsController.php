<?php

    namespace app\modules\managers\controllers;
    use Yii;
    use app\modules\managers\controllers\AppManagersController;
    use app\modules\managers\models\Clients;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\models\User;
    

/**
 * Клиенты
 */
class ClientsController extends AppManagersController {
    
    public function actionIndex() {
        
        $client_list = new ActiveDataProvider([
            'query' => Clients::getListClients(),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 30,
            ]
        ]);
        
        return $this->render('index', [
            'client_list' => $client_list,
        ]);
        
    }
    
    public function actionBlockClient() {
                
        $client_id = Yii::$app->request->post('clientId');
        $status = Yii::$app->request->post('statusClient');
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $user_info = User::findByClientId($client_id);
            $user_info->block($client_id, $status);
            return ['status' => $status, 'client_id' => $client_id];
        }
        
        return ['status' => false];
    }
    
}
