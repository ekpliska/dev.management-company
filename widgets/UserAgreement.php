<?php

    namespace app\widgets;
    use yii\base\Widget;
    use app\models\SiteSettings;

/**
 * Виджет вызовать модального окна,
 * Пользовательское соглашение
 */
class UserAgreement extends Widget {
    
    public $agreement_text;
    
    public function init() {
        
        $this->agreement_text = SiteSettings::find()
                ->indexBy('id')
                ->asArray()
                ->one();
        
        parent::init();
        
    }
    
    public function run() {
        
        return $this->render('useragreement/default', [
            'user_agreement' => $this->agreement_text,
        ]);
        
    }
    
}
