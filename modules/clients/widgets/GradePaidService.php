<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Виджет оценки для выполненной заявки на платную услугу
 */
class GradePaidService extends Widget {
    
    // ID Заявки на платную услугу
    public $request_id;
    // Статус заявки
    public $request_status = 0;
    // Оценка заявки
    public $request_grade = null;
    
    public function init() {
        
        parent::init();
        
        if ($this->request_id == null) {
            throw new \yii\base\UnknownPropertyException('Ошибка при передаче параметра');
        }
        
    }
    
    public function run() {
        
        return $this->render('gradepaidservice/default', [
           'id' => $this->request_id,
            'status' => $this->request_status,
            'grade' => $this->request_grade
        ]);
        
    }
    
    
}
