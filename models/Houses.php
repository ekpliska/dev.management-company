<?php

namespace app\models;

use Yii;

/**
 * Информация о жилых помещениях
 */
class Houses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'houses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['houses_porch', 'houses_floor', 'houses_flat', 'houses_rooms', 'houses_square', 'houses_account_id'], 'integer'],
            [['houses_name'], 'string', 'max' => 70],
            [['houses_town'], 'string', 'max' => 50],
            [['houses_street'], 'string', 'max' => 100],
            [['houses_number_house'], 'string', 'max' => 10],
        ];
    }
    
    public function getAdress() {
        return $this->houses_town . ' ' .
                $this->houses_street . ' ' .
                $this->houses_number_house . ' ' .
                $this->houses_flat;
    }
    
    public function getPorch() {
        return $this->houses_porch;
    }

    public function getFloor() {
        return $this->houses_floor;
    }    
    
    public function getRooms() {
        return $this->houses_rooms;
    }     
    
    public function getSquare() {
        return $this->houses_square;
    }     
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'houses_id' => 'Houses ID',
            'houses_name' => 'Houses Name',
            'houses_town' => 'Houses Town',
            'houses_street' => 'Houses Street',
            'houses_number_house' => 'Houses Number House',
            'houses_porch' => 'Houses Porch',
            'houses_floor' => 'Houses Floor',
            'houses_flat' => 'Houses Flat',
            'houses_rooms' => 'Houses Rooms',
            'houses_square' => 'Houses Square',
            'houses_account_id' => 'Houses Account ID',
        ];
    }
}
