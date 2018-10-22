<?php

    namespace app\modules\clients\controllers;
    use Yii;
    use app\modules\clients\controllers\AppClientsController;
    use app\models\Voting;

/**
 * Голосование
 */
class VotingController extends AppClientsController {
    
    /*
     * Голосование, главная страница
     */
    public function actionIndex() {
        
        $estate_id = Yii::$app->userProfile->_user['estate_id'];
        $house_id = Yii::$app->userProfile->_user['house_id'];
        $flat_id = Yii::$app->userProfile->_user['flat_id'];        
        
        $voting_list = Voting::findAllVotingForClient($estate_id, $house_id, $flat_id);
        
        return $this->render('index', [
            'voting_list' => $voting_list,
        ]);
        
    }
    
}
