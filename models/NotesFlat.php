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
    
    const SCENARIO_ADD_NOTE = 'create new note';
    
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
            
            [['notes_flat_id', 'notes_name'], 'required', 'on' => self::SCENARIO_ADD_NOTE],
            ['notes_name', 'string', 'min' => 5, 'max' => 255, 'on' => self::SCENARIO_ADD_NOTE],
            
        ];
    }
    /**
     * Связь с таблцией Квартиры
     */
    public function getFlat() {
        return $this->hasOne(Flats::className(), ['flats_id' => 'notes_flat_id']);
    }

    /*
     * После добавления примечания к квартире,
     * устанавливаем статус Должник для квартиры
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $flat = Flats::findOne($this->notes_flat_id);
            $flat->status = Flats::STATUS_DEBTOR_YES;
            $flat->save(false);
        }
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'notes_id' => 'Notes ID',
            'notes_flat_id' => 'Квартира',
            'notes_name' => 'Примечание',
        ];
    }

}
