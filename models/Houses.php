<?php

    namespace app\models;
    use Yii;
    use yii\db\Expression;
    use yii\db\ActiveRecord;
    use app\models\Clients;
    use yii\helpers\ArrayHelper;

/**
 * Информация о жилых помещениях
 */
class Houses extends ActiveRecord
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
    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['client_id' => 'houses_account_id']);
    }      
        
    
    public static function findByAccountId($account_id) {
        return self::find()
                ->andWhere(['houses_account_id' => $account_id])
                ->one();
    }
    
    /*
     * Получить список квартир закрепленных за собственником
     */
    public static function findByClientID($client_id) {
        $_list = self::find()
                ->andWhere(['houses_client_id' => $client_id])
                ->andWhere(['IS', 'houses_account_id', (new Expression('Null'))])
                ->asArray()
                ->all();
        
        return
            ArrayHelper::map($_list, 'houses_id', function ($array) {
                return 
                    $array['houses_town'] . ' ' .
                    $array['houses_street'] . ' ' .
                    $array['houses_number_house'] . ' ' .
                    $array['houses_flat'];
            });
        
    }
    
    public function getAdress() {
        return $this->houses_town . ' г., ' .
                $this->houses_street . ' ул., ' .
                $this->houses_number_house . ' д., ' .
                $this->houses_flat . ' кв.';
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
