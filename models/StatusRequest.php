<?php

    namespace app\models;
    use yii\helpers\ArrayHelper;

/**
 * Статусы заявок
 * Бесплатные и платные услуги
 */
class StatusRequest {
    
    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_PERFORM = 2;
    const STATUS_FEEDBACK = 3;
    const STATUS_CLOSE = 4;
    const STATUS_REJECT = 5;
    const STATUS_CONFIRM = 6;
    const STATUS_ON_VIEW = 7;
    
    /*
     * Массив статусов заявок
     */
    public static function getStatusNameArray() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBACK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
            self::STATUS_REJECT => 'Отклонена',
            self::STATUS_CONFIRM => 'Подтверждена пользователем',
            self::STATUS_ON_VIEW => 'На рассмотрении',
        ];
    }
    
    /*
     * Массив статусов заявок для пользователя
     */
    public static function getUserStatusRequests() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBACK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
        ];       
    }    

    /*
     * Массив статусов заявок для пользователя
     */
    public static function getUserRequests() {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_IN_WORK => 'in_work',
            self::STATUS_PERFORM => 'perform',
            self::STATUS_FEEDBACK => 'feedback',
            self::STATUS_CLOSE => 'close',
        ];       
    }        
    
    /*
     * Получить название статуса по его номеру
     */
    public static function statusName($status) {
        return ArrayHelper::getValue(self::getStatusNameArray(), $status);
    }
    
}
