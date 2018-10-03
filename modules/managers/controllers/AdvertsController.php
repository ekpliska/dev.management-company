<?php

    namespace app\modules\managers\controllers;
    use yii\data\ActiveDataProvider;
    use app\modules\managers\models\News;
    use app\modules\managers\controllers\AppManagersController;

/**
 * Рекламные публикации
 */
class AdvertsController extends AppManagersController {
    
    public function actionIndex() {
        
        $all_adverts = new ActiveDataProvider([
            'query' => News::getAllNews($adver = true),
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15,
            ],
        ]);        
        
        return $this->render('index', [
            'all_adverts' => $all_adverts,
        ]);
    }
    
}
