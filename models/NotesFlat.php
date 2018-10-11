<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Flats;

/**
 *
 */
class NotesFlat extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'notes_flat';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['notes_flat_id', 'notes_name'], 'required'],
            [['notes_flat_id'], 'integer'],
            [['notes_name'], 'string', 'max' => 255],
            [['notes_flat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flats::className(), 'targetAttribute' => ['notes_flat_id' => 'flats_id']],
        ];
    }
    /**
     * Связь с таблцией Квартиры
     */
    public function getFlat() {
        return $this->hasOne(Flats::className(), ['flats_id' => 'notes_flat_id']);
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'notes_id' => 'Notes ID',
            'notes_flat_id' => 'Notes Flat ID',
            'notes_name' => 'Notes Name',
        ];
    }

}
