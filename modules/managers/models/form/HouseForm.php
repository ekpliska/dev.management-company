<?php

    namespace app\modules\managers\models\form;
    use yii\base\Model;
    use app\models\HousingEstates;
    use app\models\Houses;
    use app\models\CharacteristicsHouse;

/**
 *  Создание нового жилого объекта (ЖК + Дом)
 */
class HouseForm extends Model {

    private $_estate;
    private $_house;
    private $_characteristics;
    
    public function rules() {
        return [
            ['HousingEstates', 'safe'],
            ['Houses', 'required'],
            ['CharacteristicsHouse', 'safe'],
        ];
    }
    
    public function getEstate() {
        return $this->_estate;
    }
    
    public function setEstate($estate) {
        if ($estate instanceof HousingEstates) {
            $this->_estate = $estate;
        } elseif (is_array($estate)) {
            $this->_estate->setAttributes($estate);
        }
    }
    
    public function getHouse() {
        if ($this->_house === null) {
            if ($this->estate->isNewRecord) {
                $this->_house = new Houses();
            } else {
                $this->_house = $this->estate->house;
            }
        }
        return $this->_house;
    }
    
    public function setHouse($house) {
        if (is_array($house)) {
            $this->house->setAttributes($house);
        } elseif ($house instanceof Houses) {
            $this->_house = $house;
        }
    }
    
    
    
    
}
