<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use yii\base\UnknownPropertyException;
    use app\models\CategoryServices;

/**
 * Нввигационное меню на странице "Платные услуги"
 */
class SubBarPaidService extends Widget {

    private $category_list;
    
    public function init() {
        
        if ($this->getNameSevices() == null) {
            throw new UnknownPropertyException('Ошибка передечи параметров для виджета навигационного меню, платные услуги');
        }
        
        parent::init();
    }
    
    public function run() {

        return $this->render('subbarpaidservice/default', ['category_list' => $this->getNameSevices()]);
    }
    
    /*
     * Получить список всех категорий платных заявок
     */
    private function getNameSevices() {
        
        return $this->category_list = CategoryServices::getCategoryNameArray();
        
    }
    
}
