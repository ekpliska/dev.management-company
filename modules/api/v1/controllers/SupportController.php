<?php

    namespace app\modules\api\v1\controllers;
    use Yii;
    use yii\rest\Controller;
    use app\models\FaqSettings;
    use app\models\SiteSettings;
    use app\modules\api\v1\models\payments\Payments;

/**
 * Часто задаваемые вопросы
 */
class SupportController extends Controller {
    
    public function verbs() {
        
        return [
            'index' => ['get'],
            'user-agreement' => ['get'],
            'post3ds' => ['post'],
        ];
    } 
    
    /*
     * Часто задаваемые вопросы
     */
    public function actionIndex() {
        
        $questions_list = FaqSettings::find()
                ->select(['faq_question', 'faq_answer'])
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
        return $user_agreement ? 
                ['agreement' => strip_tags($user_agreement->user_agreement)] 
                : ['agreement' => 'Приносим свои извинения, пользовательское соглашение не запонено'];
    }
    
        /*
     * Проведение платежа
     * 
     * на API платежной системы (из POST)
     *      $md              параметр TransactionId из ответа сервера
     *      $pa_res          Значение одноименного параметра
     * 
     * на наш API
     *      $account_number  Номер лицевого счета
     *      $period          Период оплаты квитанции
     */
    public function actionPost3ds($account_number, $period) {
        
        $data_md = Yii::$app->request->getBodyParam('MD');
        $pa_res = Yii::$app->request->getBodyParam('PaRes');
        
        $payment = Payments::setStatusPayment($data_md, $pa_res, $account_number, $period);
        
        return true;
    }

    
}