<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Organizations;
    use app\models\FaqSettings;

/**
 * Футер
 */
class Footer extends Widget {
    
    public $organizations_info;
    public $faq_info;

    public function init() {
        
        $this->organizations_info = Organizations::find()
                ->where(['organizations_id' => 1])
                ->asArray()
                ->one();
        
        $this->faq_info = FaqSettings::find()->all();
        
    }
    
    public function run() {
        
        return $this->render('footer/default', [
            'organizations_info' => $this->organizations_info,
            'faq_info' => $this->faq_info,
        ]);
        
    }
    
}
