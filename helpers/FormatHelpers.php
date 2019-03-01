<?php
    namespace app\helpers;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\helpers\StringHelper;
    use yii\helpers\Html;
    use app\models\StatusRequest;
    use app\models\Voting;

/*
 * Внутренний хелпер,
 * предназначен для форматирования выводимых элементов
 */    
class FormatHelpers {
    
    /*
     * Форматирование строки вывода урл для Прикрепленных файлов к заявке
     */
    public static function formatUrlFileRequest($file) {
        
        if ($file == null) {
            return 'Фотографии, закрепленный за заявкой не найдены';
        }
        
        // Формируем путь
        $path = Yii::getAlias('@web') . '/upload/store/' . $file;
        
//        <a class="test-popup-link" href="path-to-image.jpg">Open popup</a>
                
        // Формируем html
        $html_path = Html::beginTag('a', ['href' => $path, 'class' => 'image-link'])
                . Html::img($path, ['class' => 'info-img'])
                . Html::endTag('a');
        
        
        return $html_path;
        
    }
    
    /*
     * Формирование ссылки на прикрепленный документ 
     */
    public static function formatUrlByDoc($file_name, $path) {
        
        $link = Yii::getAlias('@web' . '/upload/store/') . $path;
        $options = ['target' => '_blank', 'class' => '', 'download' => ''];
        
        if (empty($file_name) || empty($path)) {
            return Html::a('Документ', $link, $options);
        }
        
        return Html::a($file_name, $link, $options);
        
    }
    
    /*
     * Формирование ссылки на новость или голосование
     * Если slug содержит число, то это голосование - иначе новость
     */
    public static function formatUrlNewsOrVote($title, $slug) {
        
        $path = '';
        
        if (is_numeric($slug)) {
            $path = ['voting/view-voting', 'voting_id' => $slug];
        } elseif (is_string($slug)) {
            $path = ['news/view-news', 'slug' => $slug];
        }
        
        return Html::a($title, $path);
    }
    
    /*
     * Форматирование вывода даты в комментариях на странице заявки
     * число месяц г. (в ЧЧ:ММ)
     * @param boolean $time Переключатель отображения времени
     *      Формат:
     *          $format = 0 -> ЧЧ:ММ:СС
     *          $format = 1 -> в ЧЧ:ММ
     */
    public static function formatDate($date_int, $time = false, $format = 0, $only_time = false) {
        
        if (empty($date_int)) {
            return 'Не установлена';
        }
        
        $_date_int = Yii::$app->formatter->asDate($date_int, 'dd.MMMM.yyyy');
        
        if ($time && $format == 0) {
            $_time = Yii::$app->formatter->asTime($date_int, 'short');
        } elseif ($time && $format == 1) {
            $_time = ' в ' . Yii::$app->formatter->asTime($date_int, 'short');
        } else {
            $_time = '';
        }
        
        list($day, $month, $year) = explode('.', $_date_int);
        $_date = $only_time == false ? $day .' '. $month .' '. $year . ' г. ' : '';
        
        $date_full = $_date . $_time;
        
        return $date_full;
    }
    
    /*
     * Формирование даты сообщения в чате заявки
     */
    public static function formatDateChat($prev_date, $current_date) {
        
        // Текущий год
        $year_now = date('Y');
        // Год даты текущего сообщения
        $year_message = Yii::$app->formatter->asDate($current_date, 'Y');
        
        // Дата предыдущего сообщения
        $prev = Yii::$app->formatter->asDate($prev_date, 'short');
        // Дата текущего сообщения
        $current = Yii::$app->formatter->asDate($current_date, 'short');

        // Задаем формат вывод сообщения (выводим год сообщения, если год сообщения не соответствует текущему)
        $format = $year_now == $year_message ? 'd MMMM' : 'd MMMM Y';
        $date_print = Yii::$app->formatter->asDate($current_date, $format);
        
        if ($prev != $current) {
            return '<span>' . $date_print . '</span>';
        } 
        elseif ($current != $prev) {
            return false;
        }
        
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
     * Вывод текстового значения статусов для голосования
     */
    public static function statusNameVoting($status) {
        
        return ArrayHelper::getValue(Voting::getStatusVoting(), $status);
        
    }    
    
    /*
     * Форматирование полного имени пользователя
     * Фамилия И. О.
     */
    public static function formatFullUserName($surname, $name, $second_name, $full = false) {
        
        if ($surname == null && $name == null && $second_name == null) {
            return 'Не задан';
        }
        
        $_name = $full ? $name : mb_substr($name, 0, 1, 'UTF-8') . '.';
        $_second_name = $full ? $second_name : mb_substr($second_name, 0, 1, 'UTF-8') . '.';
        
        return $surname . ' ' . $_name . ' ' . $_second_name . ' ';
        
    }
    
    /*
     * Вывод фотографии пользователя
     */
    public static function formatUserPhoto($user_photo) {
        
        $alias = Yii::getAlias('@web');
        
        if (empty($user_photo)) {
            return Html::img($alias . '/images/no-avatar.jpg', ['alt' => 'no_avatar', 'class' => 'img-circle', 'width' => '50px']);
        }
        
        return Html::img($user_photo, ['alt' => 'user_avatar', 'class' => 'img-circle', 'width' => '50px']);
        
    }
    
    /*
     * Форматирование полного адреса проживания
     * Полный адрес ГИС, д. Номер, п. Подъезд, эт. Этаж, кв. Номер
     */
    public static function formatFullAdress($gis_adress, $house, $porch = false, $floor = false, $flat = false) {
        
        $gis_adress = $gis_adress ? $gis_adress . ', ' : '';
        
        $house = ($porch || $floor || $flat)  ? 'д. ' . $house . ', ' : 'д. ' . $house;
        $porch = $porch ? 'подъезд ' . $porch . ', ' : '';
        $floor = $floor ? 'эт. ' . $floor . ', ' : '';
        $flat = $flat ? 'кв. ' . $flat : '';
        
        return $gis_adress .  $house . $porch . $floor . $flat;
    }
    
    /*
     * Форматирование полного адреса жилого дома для списка домов в "Жилищном фонде"
     */
    public static function formatHousingStosk($gis_adress, $house) {
        
        $gis_array = explode(',', $gis_adress);
        $full_adress = '';
        
        for ($i = 3; $i < count($gis_array); $i++) {
            $full_adress .= $gis_array[$i] . ', ';
        }
        
        return $full_adress . ' ' . $house;
    }
    
    /*
     * Форматирование адреса Квартиты
     * Квартира Номер. подъеда Номер
     */
    public static function flatAndPorch($number_flat, $porch) {
        return 'Квартира ' . $number_flat . ', подьезд ' . $porch;
    }
    
    /*
     * Форматирование суммы баланса Собственика лицевого счета
     * Отрицательный баланс, для наглядности, подсвечиваем другим цветом
     */
    public static function formatBalance($balance) {
        
        if ($balance < 0) {
            return '<span style="color: red">' . $balance . '</span>';
        }
        
        return $balance;
        
    }
    
    /*
     * Формирование превью новости, голосования
     * Главная страница личного кабинета собственника
     * @param boolean $full Превью, (true - для отдельной страницы)
     */
    public static function previewNewsOrVote($path, $full = false) {
        
        if (empty($path)) {
            $full_path = '/web/images/not_found.png';
        } else {
            $full_path = "/web/$path";
        }
        
        $class_css = $full ? 'news-image' : 'news-card-img';
        
        return Html::img($full_path, ['class' => $class_css]);
        
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


    /*
     * Форматирование заголовка или текста публикации, голосования
     * в зависимости от указанной длины 
     * 
     * @param $count_letter integer Количество выводимых символов
     */
    public static function shortTitleOrText($text, $count_letter = 25) {
        
        if (empty($text)) {
            return 'Заголовок/тизер не заданы';
        }
        // Удаляем все html теги
        $_text = strip_tags($text);
        return StringHelper::truncate($_text , $count_letter, '...');
        
    }    
    
    
    /*
     * Подсчет количества дней до завершения голосования
     */
    public static function numberOfDaysToFinishVote($date_start, $date_end) {
        
        if (empty($date_start) || empty($date_end)) {
            return 'Не определено';
        }
        
        $date_now = date_create(date('Y-m-d H:i:s'));
        $_end = date_create($date_end);
        $interval = date_diff($date_now, $_end);
        
        $image_clock = '<i class="glyphicon glyphicon-time"></i>';

        if (strtotime($date_end) > time() && $interval->d !== 0) {
            
            $message = $image_clock . Yii::$app->i18n->messageFormatter->format(
                    'До окончания {n, plural, one{# день} few{# дня} many{# дней} other{# дней}}',
                    ['n' => $interval->d],
                    Yii::$app->language);
            
        } elseif ($interval->d == 0 && $interval->h !== 0) {
            
            $message = $image_clock . Yii::$app->i18n->messageFormatter->format(
                    'До окончания {n, plural, one{# час} few{# часа} many{# часов} other{# часов}}',
                    ['n' => $interval->h],
                    Yii::$app->language);
            
        } elseif ($interval->d == 0 && $interval->h == 0 && $interval->i !== 0) {
            
            $message = $image_clock . Yii::$app->i18n->messageFormatter->format(
                    'До окончания {n, plural, one{# минута} few{# минуты} many{# минут} other{# минут}}',
                    ['n' => $interval->i],
                    Yii::$app->language);
            
        } else {
            $message = 'Завершено';
        }
        return $message;
    }
    
    public static function statusVotingInView($status) {
        
        $message = '';
        
        if ($status == Voting::STATUS_ACTIVE) {
            $message =  '<i class="glyphicon glyphicon-ok"></i> Идет обсуждение';
        } 
        elseif ($status == Voting::STATUS_CLOSED) {
            $message =  '<i class="glyphicon glyphicon-flag"></i> Завершено';
        }
        
        return $message;
        
    }
    
    /*
     * Формирование списка изображений прикрепленных к заяке
     * @param objec $image_list
     */
    public static function imageRequestList($image_list) {
        
        $lists = '';
        
        if (empty($image_list)) {
            return false;
        }
        
        foreach ($image_list as $key => $image) {
            $list = '<img src="' . '/web/upload/store/' . $image->filePath . '" class="request">';
            $lists .= $list;
        }
        
        return $lists;
    }
    
    /*
     * Формирование списка изображений прикрепленных к заяке
     * @param array $image_list
     */
    public static function imageRequestListByDispatcher($image_list) {
        
        $lists = '';
        
        if (empty($image_list)) {
            return false;
        }
        
        foreach ($image_list as $key => $image) {
            $list = Html::img('@web/upload/store/' . $image['filePath']);
            $lists .= $list;
        }
        
        return $lists;
    }
    
}
