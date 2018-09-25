<?php
    namespace app\helpers;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\helpers\StringHelper;
    use yii\helpers\Html;
    use app\models\StatusRequest;

/*
 * Внутренний хелпер,
 * предназначен для форматирования выводимых элементов
 */    
class FormatHelpers {
    
    /*
     * Форматирование строки вывода урл для Прикрепленных файлов к заявке
     */
    public static function formatUrlFileRequest($file) {
        
        $array_exension = [
            'jpg' => '1',
            'png' => '2',
            'jpeg' => '3'
        ];
        
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        
        if (ArrayHelper::keyExists($extension, $array_exension)) {
            return Html::a('Изображение', $file, ['target' => '_blank']);
        } else {      
            return Html::a('Файл', $file);
        }        
    }
    
    /*
     * Форматирование вывода даты в комментариях на странице заявки
     * число месяц г. часы:минуты:секунды
     */
    public static function formatDate($date_int) {
        if (empty($date_int)) {
            return 'Не установлена';
        }
        $_date_int = Yii::$app->formatter->asDate($date_int, 'dd.MMMM.yyyy');
        $_time = Yii::$app->formatter->asTime($date_int, 'H:i:s');
        list($day, $month, $year) = explode('.', $_date_int);
        $date_full = $day .' '. $month .' '. $year . ' г. ' . $_time;
        return $date_full;
    }
    
    /*
     * Форматирование вывода даты
     * Лицевой счет / Приборы учета
     */
    public static function formatDateCounter($date_int) {
        return Yii::$app->formatter->asDate($date_int, 'd MMMM Y');
    }
    
    /*
     * Вывод текстового значения статусов для платных и бесплатных заявок
     */
    public static function statusName($status) {
        
        return ArrayHelper::getValue(StatusRequest::getStatusNameArray(), $status);
        
    }
    
    /*
     * Форматирование полного имени пользователя
     * Фамилия И. О.
     */
    public static function formatFullUserName($surname, $name, $second_name) {
        
        $_name = mb_substr($name, 0, 1, 'UTF-8');
        $_second_name = mb_substr($second_name, 0, 1, 'UTF-8');
        
        return $surname . ' ' . $_name . '. ' . $_second_name . '.';
        
    }
    
    /*
     * Форматирование полного адреса проживания
     * г. Город, ул. Улица, д. Номер, эт. Этаж, кв. Номер
     */
    public static function formatFullAdress($town, $street, $house, $floor, $flat) {
        
        $town = $town ? 'г. ' . $town : '';
        $street = $street ? 'ул. '  . $street : '';
        $house = $house ? 'д. ' . $house : '';
        $floor = $floor ? 'эт. ' . $floor : '';
        $flat = $flat ? 'кв. ' . $flat : '';
        
        return $town .  ', ' . $street . ', ' . $house . ', ' . $floor . ', ' . $flat;
    }
    
    /*
     * Форматирование суммы баланса Собственика лицевого счета
     * 
     * 
     * Отрицательный баланс, для наглядности, подсвечиваем другим цветом
     */
    public static function formatBalance($balance) {
        
        if ($balance < 0) {
            return '<span style="color: red">' . $balance . '</span>';
        }
        
        return $balance;
        
    }
    
}
