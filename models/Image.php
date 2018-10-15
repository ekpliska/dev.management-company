<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\Html;

/**
 * Модель, контролирующая сохранение изображений
 * Используется для расширения yii2-image
 */
class Image extends ActiveRecord
{
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['filePath', 'modelName', 'urlAlias'], 'required'],
            [['itemId', 'isMain'], 'integer'],
            [['filePath', 'urlAlias'], 'string', 'max' => 400],
            [['modelName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 80],
        ];
    }
    
    /*
     * Получить полный путь к загруженному файлу
     */
    public function getImagePath($image) {
        return '@web/upload/store/' . $image;
    }
    
    /*
     * Получить все документы, закрепленные за публикацией
     */
    public static function getAllDocByNews($news_id, $model_name) {
        return self::find()
                ->where(['itemId' => $news_id])
                ->andWhere(['modelName' => $model_name])
                ->asArray()
                ->all();
    }

    /*
     * Получить все документы, закрепленные за публикацией
     */
    public static function getAllFilesByHouse($house_id, $model_name) {
        return self::find()
                ->where(['itemId' => $house_id])
                ->andWhere(['modelName' => $model_name])
                ->asArray()
                ->all();
    }    
    
    public function afterDelete() {
        
        parent::afterDelete();
        $path = $this->filePath;
        @unlink(Yii::getAlias('@webroot') . '/upload/store/' . $path);
    }
    
//    public function afterDelete() {
//        parent::afterDelete();
//        $this->getPathImageInText($this->news_text);
//        $preview = $this->news_preview;
//        @unlink(Yii::getAlias('@webroot') . $preview);
//    }    

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filePath' => 'File Path',
            'itemId' => 'Item ID',
            'isMain' => 'Is Main',
            'modelName' => 'Model Name',
            'urlAlias' => 'Url Alias',
            'name' => 'Name',
        ];
    }
}
