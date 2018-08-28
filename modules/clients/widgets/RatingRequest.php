<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use app\models\StatusRequest;

/**
 * Description of RatingRequest
 *
 * @author Ekaterina
 */
class RatingRequest extends Widget {
    
    // Статус заявки
    public $_status = 0;
    // ID заявки
    public $_request_id;
    
    public function init() {
        
        // Если передан не верный статус или ID заявки, кидаем исключение
        if (!ArrayHelper::keyExists($this->_status, StatusRequest::getStatusNameArray()) || empty($this->_request_id)) {
            throw new \yii\base\UnknownPropertyException('Ошибка при передаче параметра');
        }
        
        parent::init();
        
    }
    
    public function run() {
        
        // Вид виждета отрисовываем только для заявок со статусом закрыто
        if ($this->_status == StatusRequest::STATUS_CLOSE) {
            return $this->render('ratingrequest/rating_view', ['status' => $this->_status]);
        }
        
    }
    
}
