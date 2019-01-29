<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Houses;
    use app\models\NotesFlat;
    use app\models\PersonalAccount;
    use app\models\Clients;

/**
 * Квартиры
 */
class Flats extends ActiveRecord
{
    // Задолженность на квартире
    const STATUS_DEBTOR_YES = 1;
    const STATUS_DEBTOR_NO = 0;
    
    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'flats';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['flats_house_id', 'flats_porch', 'flats_floor', 'flats_number', 'flats_rooms', 'flats_square'], 'required'],
            [['flats_house_id', 'flats_porch', 'flats_floor', 'flats_number', 'flats_rooms', 'is_debtor'], 'integer'],
            [['flats_square'], 'double', 'min' => 20.00, 'max' => 1000.00],
            [['flats_house_id'], 'exist', 'skipOnError' => true, 'targetClass' => Houses::className(), 'targetAttribute' => ['flats_house_id' => 'houses_id']],
        ];
    }
    
    public function getAccount() {
        return $this->hasOne(PersonalAccount::className(), ['personal_flat_id' => 'flats_id']);
    }

    /**
     * Связь с таблицей Дома
     */
    public function getHouse() {
        return $this->hasOne(Houses::className(), ['houses_id' => 'flats_house_id']);
    }    
    
    /*
     * Связь с таблицей Примечания к квартире
     */
    public function getNote() {
        return $this->hasMany(NotesFlat::className(), ['notes_flat_id' => 'flats_id']);
    }
    
    /*
     * Поиск по ID квартиры
     */
    public static function findById($flat_id) {
        return self::find()
                ->where(['flats_id' => $flat_id])
                ->one();
    }
    
    /*
     * Снимаем статус "Должник" с квартиры
     */
    public function takeOffStatus(){
        
        $this->is_debtor = self::STATUS_DEBTOR_NO;
        return $this->save(false) ? true : false;
        
    }
    
    /*
     * Получить список всех квартир по заданному дому
     */
    public static function getFlatsByHouse($house_id){
        
        $list = self::find()
                ->select([
                    'flats_id', 'flats_porch', 'flats_number', 'flats.is_debtor', 
                    'clients_surname', 'clients_name', 'clients_second_name', 
                    'user_photo'])
                ->joinWith(['account', 'account.client', 'account.client.user', 'note'])
                ->where(['flats_house_id' => $house_id])
                ->asArray()
                ->orderBy(['flats_porch' => SORT_ASC, 'flats_number' => SORT_ASC])
                ->all();
        
        return $list;
        
    }
    
    /*
     * После снятия статуса "Должник" удаляем все примечанию по квартире
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if ($changedAttributes['is_debtor'] == self::STATUS_DEBTOR_YES) {
            NotesFlat::deleteAll(['notes_flat_id' => $this->flats_id]);
        }
    }
    
    /*
     * Получить полный адрес
     */
    public function getFullAdress() {
        
        $adress = Flats::find()
                ->joinWith(['house']);
        
        return $adress;
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'flats_id' => 'Flats ID',
            'flats_house_id' => 'Flats House ID',
            'flats_porch' => 'Flats Porch',
            'flats_floor' => 'Flats Floor',
            'flats_number' => 'Flats Number',
            'flats_rooms' => 'Flats Rooms',
            'flats_square' => 'Flats Square',
        ];
    }

}
