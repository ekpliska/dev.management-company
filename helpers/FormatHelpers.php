<?php
    namespace app\helpers;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

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
            'jepg' => '3'
        ];
        
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        
        if (ArrayHelper::keyExists($extension, $array_exension)) {
            return Html::a('Изображение', $file, ['target' => '_blank']);
        } else {      
            return Html::a('Файл', $file);
        }        
    }
}
