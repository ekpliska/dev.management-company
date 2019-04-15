<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Services;

/**
 * Виджет вывода последних новых услуг
 */
class LastServices extends Widget {
    
    // Количество услуг на странице
    public $count = 2;
    
    // Список услуг
    public $services_lists;

    public function init() {
        
        $this->services_lists = Services::find()
                ->limit($this->count)
                ->orderBy(['service_id' => SORT_DESC])
                ->all();
        
        parent::init();
    }
    
    public function run() {
        
        return $this->render('lastservices/default', [
            'services_lists' => $this->services_lists,
        ]);
        
    }
    
}
