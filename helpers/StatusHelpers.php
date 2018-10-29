<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\StatusRequest;    

/**
 * Формирование вывода статусов
 * Заявок
 */
class StatusHelpers {
    
    public static function requestStatus($status) {
        
        $btn_css = '';
        
        // Стили для кнопок статусов
        $css_classes = [
            'badge badge-pill req-badge req-badge-new', 
            'badge badge-pill req-badge req-badge-work', 
            'badge badge-pill req-badge req-badge-complete', 
            'badge badge-pill req-badge req-badge-rectification', 
            'badge badge-pill req-badge req-badge-close',
        ];
    
        
        if (!isset($status)) {
            return 'Не задан';
        }
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<p><span class="' . $css_classes[0] . '">' . $status_name . '</span></p>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<p><span class="' . $css_classes[1] . '">' . $status_name . '</span></p>';
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<p><span class="' . $css_classes[2] . '">' . $status_name . '</span></p>';
        } elseif ($status == StatusRequest::STATUS_FEEDBAK) {
            $btn_css = '<p><span class="' . $css_classes[3] . '">' . $status_name . '</span></p>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<p><span class="' . $css_classes[4] . '">' . $status_name . '</span></p>';
        }
        
        return $btn_css;
        
    }
    
}
