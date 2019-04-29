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
     * @param integet $status Статус заявки
     * @param integet $value ID завяки
     * @param boolean $client Рендер звездочек для Собственника (В противном случае на Администратора, Диспетчера)
     * @param integet $grade Оценка
     */
    public static function requestStatus($status, $value = null, $client = true, $grade = null) {
        
        $btn_css = '';
        $voting_bnt = '';
        
        // Стили для кнопок статусов
        $css_classes = [
            'badge req-badge-new', 
            'badge req-badge-work', 
            'badge req-badge-complete', 
            'badge req-badge-rectification', 
            'badge req-badge-close',
            'badge req-badge-reject',
        ];
    
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBACK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_REJECT) {
            $btn_css = '<span class="' . $css_classes[5] . '">' . $status_name . '</span>';
        }
        
        if ($status == StatusRequest::STATUS_CLOSE && $client == true) {
//            $voting_bnt = Html::button('Оценить', ['class' => 'blue-outline-btn req-table-btn', 'data-request' => $value]);
//            $voting_bnt = '<div id="star" data-request="' . $value . '" data-score-reguest="' . $grade . '"></div>';
        }
        
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
            'req-badge-reject-page badge-page',
        ];
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>'; 
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBACK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        } elseif ($status == StatusRequest::STATUS_REJECT) {
            $btn_css = '<span class="' . $css_classes[5] . '">' . '<span>' . $status_name .  '</span><span>' . $date_full . '</span></span>';
        }
        
        return $btn_css;
        
    }
    
    public static function reportStatus($status) {
        
        // Стили для кнопок статусов
        $css_classes = [
            'reposrt-status req-new', 
            'reposrt-status req-work', 
            'reposrt-status req-complete', 
            'reposrt-status req-rectification', 
            'reposrt-status req-close',
            'reposrt-status req-reject',
        ];
    
        
        // Получаем текстовое обозначение статуса
        $status_name = ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);        
        
        if ($status == StatusRequest::STATUS_NEW) {
            $btn_css = '<span class="' . $css_classes[0] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_IN_WORK) {
            $btn_css = '<span class="' . $css_classes[1] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_PERFORM) {
            $btn_css = '<span class="' . $css_classes[2] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_FEEDBACK) {
            $btn_css = '<span class="' . $css_classes[3] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_CLOSE) {
            $btn_css = '<span class="' . $css_classes[4] . '">' . $status_name . '</span>';
        } elseif ($status == StatusRequest::STATUS_REJECT) {
            $btn_css = '<span class="' . $css_classes[5] . '">' . $status_name . '</span>';
        }
        
        return $btn_css;
        
    }
    
}
