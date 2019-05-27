<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use Yii;
    use yii\helpers\ArrayHelper;
    use rico\yii2images\behaviors\ImageBehave;
    use app\models\Flats;
    use app\models\CharacteristicsHouse;
    use app\models\Image;

/**
 * Дома
 */
class Houses extends ActiveRecord
{
    const SCENARIO_EDIT_DESCRIPRION = 'edit description house';
    const SCENARIO_LOAD_FILE = 'load new file';
    
    const TYPE_OF_NEWS = 0;
    const TYPE_OF_ADVERT = 1;

    public $upload_file;
    public $upload_files;


    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'houses';
    }
    
    public function behaviors () {
        return [
            'image' => [
                'class' => ImageBehave::className(),
            ],
        ];
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['houses_gis_adress', 'required'],
            
            ['houses_gis_adress', 'string', 'max' => 255],
            
            [['houses_description'], 'string', 'max' => 255],
            
            [['houses_name'], 'string', 'max' => 100],
            
            ['houses_description', 'required', 'on' => self::SCENARIO_EDIT_DESCRIPRION],
            ['houses_description', 'string', 'max' => 255, 'on' => self::SCENARIO_EDIT_DESCRIPRION],
            
            ['upload_file', 'required', 'on' => self::SCENARIO_LOAD_FILE],
            [['upload_file'],
                'file', 
                'maxSize' => 10 * 1024 * 1000,
                'extensions' => 'doc, docx, pdf, xls, xlsx, png, jpg, jpeg',
                'on' => self::SCENARIO_LOAD_FILE],
            
            [['upload_files'],
                'file', 
                'maxSize' => 10 * 1024 * 1000,
                'extensions' => 'doc, docx, pdf, xls, xlsx, png, jpg, jpeg',
                'maxFiles' => 4,
            ],            
            
        ];
    }

    /**
     * Связь с таблицей Квартиры
     */
    public function getFlat() {
        return $this->hasMany(Flats::className(), ['flats_house_id' => 'houses_id']);
    }

    /**
     * Связь с таблицей Характеристики дома
     */
    public function getCharacteristic() {
        return $this->hasMany(CharacteristicsHouse::className(), ['characteristics_house_id' => 'houses_id']);
    }
    
    /**
     * Связь с таблицей "Вложения" (Image)
     */
    public function getImage() {
        return $this->hasMany(Image::className(), ['itemId' => 'houses_id'])->andWhere(['modelName' => 'Houses']);
    }
    
    public static function findHouseById($house_id) {
        return self::find()
                ->where(['houses_id' => $house_id])
                ->one();
    }
    
    public static function getTypePublication() {
        
        return [
            self::TYPE_OF_NEWS => 'Новости',
            self::TYPE_OF_ADVERT => 'Реклама',
        ];
        
    }
    
    /*
     * Получить полный список домов, и квартир
     */
    public static function getAllHouses() {        
        
        $houses_list = self::find()
                ->with(['flat', 'characteristic', 'flat.account', 'flat.note', 'flat.account.client', 'image', 'flat.account.client.user'])
                ->asArray()
                ->orderBy([
                    'houses_name' => SORT_ASC,
                    'houses_gis_adress' => SORT_ASC,
                    'houses_number' => SORT_ASC])
                ->all();
        
        return $houses_list;
        
    }
    
    /*
     * Формирование списка адресов домов
     * @param boolean $for_list Если требуется предоставить список для dropDownList
     */
    public static function getHousesList($for_list = false) {
        
        $houses = self::find()
                ->select(['houses_id', 'houses_gis_adress', 'houses_number'])
                ->orderBy([
                    'houses_gis_adress' => SORT_ASC,
                    'houses_number' => SORT_ASC])
                ->asArray()
                ->all();
        
        return $for_list == false ? 
                ArrayHelper::map($houses, 'houses_id', function($data) {
                    $house_address = substr($data['houses_gis_adress'], 8);
                    return $house_address . ', ' . $data['houses_number'];
                }) : $houses;
    }    
    
    /*
     * Загрузка прикрепленного документа
     */
    public function uploadFile($file) {
        
        if ($file) {
            $file_name = $file->basename;
            $path = 'upload/store/' . $file_name . '.' . $file->extension;
            // Получаем имя файла
            $file->saveAs($path);
            $this->attachImage($path, false, $file_name);
            @unlink($path);
            return true;
        } else {
            return false;
        }
    }

    /*
     * Загрузка прикрепленных документов
     */
    public function uploadFiles($files) {
        
        if ($files) {
            foreach ($files as $file) {
                $file_name = $file->basename;
                $path = 'upload/store' . $file_name . '.' . $file->extension;
                // Получаем имя файла
                $file->saveAs($path);
                $this->attachImage($path, false, $file_name);                
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    /*
     * Список всех домов жилого массива
     * Т.к. поле в БД houses_gis_adress - содержит полную строку адреса, 
     * то из этой строки исключаем Индекс, Область, Район
     */
    public static function getAdressHousesList() {
        
        $array = self::find()
                ->asArray()
                ->orderBy(['houses_gis_adress' => SORT_ASC])
                ->all();
        
        $houses_lists = ArrayHelper::map($array, 'houses_id', function ($array) {
            $str_adress = '';
            $str_array = explode(', ', $array['houses_gis_adress']);
            // В цикле, начиная с 3 позиции получаем, город/поселок, улицу/площадь.. и т.д, заканчивая номером дома/корпус
            for ($i = 3; $i < count($str_array); $i++) {
                $str_adress .= $str_array[$i] . ', ';
            }
            return substr($str_adress, 0, -2);
        });
        
        return $houses_lists;

    }
    
    /*
     * Проверка существования Дома при регистрации и создании лицевого счета
     */
    public static function isExistence($house_id, $house_adress, $full_adress, $house_number) {
        
        // Обрезаем строку полного адреса содственника до номера дома
        $_adress = stristr($full_adress, 'д.', true);
        // Обрезаем последний символ в строке (,)
        $_adress = substr($_adress, 0, -2);
        
        $_house = self::find()
                ->where([
                    'houses_id' => $house_id])
                ->asArray()
                ->one();
        
        if ($_house == null) {
            $house = new Houses();
            $house->houses_id = $house_id;
            $house->houses_name = $house_adress;
            $house->houses_gis_adress = $_adress;
            $house->houses_number = $house_number;
            $house->save(false);
            return $house->houses_id;
        }
        
        return $_house['houses_id'];
    }
    
    /*
     * Проверка существования Дома при регистрации и создании лицевого счета
     */
//    public static function isExistence($house_adress, $full_adress, $house_number) {
//        
//        // Обрезаем строку полного адреса содственника до номера дома
//        $_adress = stristr($full_adress, 'д.', true);
//        // Обрезаем последний символ в строке (,)
//        $_adress = substr($_adress, 0, -2);
//        
//        $_house = self::find()
//                ->where([
//                    'houses_name' => $house_adress, 
//                    'houses_gis_adress' => $_adress,
//                    'houses_number' => $house_number])
//                ->asArray()
//                ->one();
//        
//        if ($_house == null) {
//            $house = new Houses();
//            $house->houses_name = $house_adress;
//            $house->houses_gis_adress = $_adress;
//            $house->houses_number = $house_number;
//            $house->save(false);
//            return $house->houses_id;
//        }
//        
//        return $_house['houses_id'];
//    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'houses_id' => 'Houses ID',
            'houses_gis_adress' => 'Адресс',
            'houses_description' => 'Описание',
            'upload_file' => 'Загружаемый файл',
            'upload_files' => 'Файлы',
        ];
    }


}
