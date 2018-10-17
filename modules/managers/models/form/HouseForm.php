<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;
    use Yii;
    use app\models\HousingEstates;
    use app\models\Houses;
    use app\models\CharacteristicsHouse;

/**
 *  Создание нового жилого объекта (ЖК + Дом)
 */
class HouseForm extends Model {

    private $_housingEstates;
    private $_houses;
//    private $_characteristics;
    
    public function rules() {
        return [
            ['HousingEstates', 'required'],
            ['Houses', 'safe'],
//            ['CharacteristicsHouse', 'safe'],
        ];
    }
    
//    public function afterValidate() {
//        if (!Model::loadMultiple($this->getAllModels())) {
//            $this->addError(null);
//        }
//        parent::afterValidate();
//    }
    
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        if (!$this->saveEstate()) {
            $transaction->rollBack();
            return false;
        }
        
        $this->houses->houses_estate_name_id = $this->housingEstates->estate_id ? $this->housingEstates->estate_id : $this->housingEstates->estate_name_drp;
        if (!$this->houses->save(false)) {
            $transaction->rollBack();
            return false;
        }
        
        $transaction->commit();
        return true;
        
    }
    
    public function saveEstate() {
        
        // Если пришел переключатель "Новый ЖК", создаем запись нового ЖК
        if ($this->housingEstates->is_new == true) {
            if (!$this->housingEstates->save()) {
                return false;
            }
        }
        return true;
    }
    
//        else {
//            $this->houses->houses_estate_name_id = $this->housingEstates->estate_name_drp;
//            return $this->houses->save() ? true : false;
//        }
    
    public function getHousingEstates() {
        return $this->_housingEstates;
    }
    
    public function setHousingEstates($estate) {
        if ($estate instanceof HousingEstates) {
            $this->_housingEstates = $estate;
        } elseif (is_array($estate)) {
            $this->_housingEstates->setAttributes($estate);
        }
    }
    
    public function getHouses() {
        if ($this->_houses === null) {
            if ($this->housingEstates->isNewRecord) {
                $this->_houses = new Houses();
            } else {
                $this->_houses = $this->housingEstates->house;
            }
        }
        return $this->_houses;
    }
    
    public function setHouses($house) {
        if (is_array($house)) {
            $this->houses->setAttributes($house);
        } elseif ($house instanceof Houses) {
            $this->_houses = $house;
        }
    }
    
    private function getAllModels() {
        
        $models = [
            'Жилой комплекс' => $this->housingEstates,
            'Дом' => $this->houses,
        ];
        
        return $models;
        
    }
    
    
}
