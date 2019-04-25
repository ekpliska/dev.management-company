<?php

    namespace app\modules\api\v1\controllers;
    use yii\rest\Controller;
    use yii\helpers\HtmlPurifier;
    use app\models\FaqSettings;
    use app\models\SiteSettings;

/**
 * Часто задаваемые вопросы
 */
class SupportController extends Controller {
    
    public function verbs() {
        
        return [
            'index' => ['get'],
            'user-agreement' => ['get'],
        ];
    } 
    
    /*
     * Часто задаваемые вопросы
     */
    public function actionIndex() {
        
        $questions_list = FaqSettings::find()
                ->select('faq_question', 'faq_answer')
                ->asArray()
                ->orderBy('id')
                ->all();
        
        return $questions_list;        
    }
    
    /*
     * Пользовательское соглашение
     */
    public function actionUserAgreement() {
        $user_agreement = SiteSettings::findOne(['id' => 1]);
        return $user_agreement ? strip_tags($user_agreement->user_agreement) : null;
    }
    
}
