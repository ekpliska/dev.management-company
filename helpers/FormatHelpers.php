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
     * Формирование ссылки на прикрепленный документ 
     */
    public static function formatUrlByDoc($file_name, $path) {
        
        $link = Yii::getAlias('@web' . '/upload/store/') . $path;
        $options = ['target' => '_blank', 'class' => 'btn btn-info btn-sm'];
        
        if (empty($file_name) || empty($path)) {
            return Html::a('Документ', $link, $options);
        }
        
        return Html::a($file_name, $link, $options);
        
    }
    
    /*
     * Форматирование вывода даты в комментариях на странице заявки
     * число месяц г.
     */
    public static function formatDate($date_int, $time = false) {
        
        if (empty($date_int)) {
            return 'Не установлена';
        }
        
        $_date_int = Yii::$app->formatter->asDate($date_int, 'dd.MMMM.yyyy');
        $_time = $time ? Yii::$app->formatter->asTime($date_int) : '';
        
        list($day, $month, $year) = explode('.', $_date_int);
        
        $date_full = $day .' '. $month .' '. $year . ' г. ' . $_time;
        
        return $date_full;
    }
    
    /*
     * Форматирование вывода даты в комментариях на странице заявки
     * @param string $date_full число месяц г. 
     * $param string $tile часы:минуты:секунды
     */
    public static function formatDateWithMonth($date_int) {
        
        if (empty($date_int)) {
            return 'Не установлена';
        }
        
        $current_year = date('Y');
        
        $_date_int = Yii::$app->formatter->asDate($date_int, 'dd.MMMM.yyyy');
        $time = Yii::$app->formatter->asTime($date_int, 'H:i:s');
        
        list($day, $month, $year) = explode('.', $_date_int);
        $year = $current_year == $year ? '' : $year . ' г.';
        
        $date_full = $day . ' ' . $month . ' ' . $year;
        
        return [
            'date' => $date_full,
            'time' => $time,
        ];
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
        
        if ($surname == null && $name == null && $second_name == null) {
            return 'Не задан';
        }
        
        $_name = mb_substr($name, 0, 1, 'UTF-8');
        $_second_name = mb_substr($second_name, 0, 1, 'UTF-8');
        
        return $surname . ' ' . $_name . '. ' . $_second_name . '.';
        
    }
    
    /*
     * Форматирование полного адреса проживания
     * г. Город, ул. Улица, д. Номер, эт. Этаж, кв. Номер
     */
    public static function formatFullAdress($town, $street, $house, $floor = false, $flat = false) {
        
        $town = $town ? 'г. ' . $town . ', ' : '';
        $street = $street ? 'ул. '  . $street . ', ' : '';
        $house = ($house && $flat) ? 'д. ' . $house . ', ' : 'д. ' . $house;
        $floor = $floor ? 'эт. ' . $floor . ', ' : '';
        $flat = $flat ? 'кв. ' . $flat : '';
        
        return $town .  $street . $house . $floor . $flat;
    }
    
    /*
     * Форматирование полного адреса жилого комплекса
     * Наименование комплекса. г. Город
     */
    public static function formatEstateAdress($name, $town) {
        
        $name = $name ? $name . ', ' : '';
        $town = $town ? 'г. ' . $town : '';
        
        return $name . $town;
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
    
    /*
     * Вывод тизера публикации
     * 
     * @param string $text Полный текст новости
     * @param integer $count_world Количество слов для тизера
     */
    public static function shortTextNews($text, $count_world = 40) {
        
        if (empty($text)) {
            return 'Текст публикации отсутствует';
        }
        // Удаляем все html теги в тексте публикации
        $_text = strip_tags($text);
        return StringHelper::truncateWords($_text , $count_world, ' [...]');
        
        
    }
    
}
