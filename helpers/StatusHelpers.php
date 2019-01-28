<?php

    namespace app\helpers;
    use Yii;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\StatusRequest;    

/**
 * Формирование вывода статусов
 * Заявок
 */
class StatusHelpers {
    
    /*
     * Формирование статусов для таблицы заявки, услуги
     */
    public static function requestStatus($status, $value = null, $client = true) {
        
        $btn_css = '';
        $voting_bnt = '';
        
        // Стили для кнопок статусов
        $css_classes = [
            'badge req-badge-new', 
            'badge req-badge-work', 
            'badge req-badge-complete', 
            'badge req-badge-rectification', 
            'badge req-badge-close',
        ];
    
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBAK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . $status_name . '</span>';
        }
        
//        if ($status == StatusRequest::STATUS_CLOSE && $client == true) {
//            $voting_bnt = Html::button('Оценить', ['class' => 'blue-outline-btn req-table-btn', 'data-request' => $value]);
//        }
        
        return $btn_css . '<br />' . $voting_bnt;
        
    }
    
    /*
     * Формирование статусов, для страницы просмотреа заявки
     */
    public static function requestStatusPage($status, $date_edit) {
        
        $btn_css = '';
        
        $date = Yii::$app->formatter->asDate($date_edit, 'long');
        $time = Yii::$app->formatter->asTime($date_edit, 'short');
        $date_full = $date . ' в ' . $time;
        
        // Стили для кнопок статусов
        $css_classes = [
            'req-badge-new-page badge-page', 
            'req-badge-work-page badge-page', 
            'req-badge-complete-page badge-page', 
            'req-badge-rectification-page badge-page', 
            'req-badge-close-page badge-page',
        ];
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>'; 
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBAK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        }
        
        return $btn_css;
        
    }    
    
}
