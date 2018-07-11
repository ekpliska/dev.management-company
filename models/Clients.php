<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Rents;
    use app\models\PersonalAccount;
    use app\models\Houses;

/**
 * Собственники
 */
class Clients extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['clients_account_id', 'is_rent'], 'integer'],
            [['clients_name', 'clients_second_name', 'clients_surname'], 'string', 'max' => 70],
            [['clients_mobile', 'clients_phone'], 'string', 'max' => 50],
        ];
    }
    
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['personal_clients_id' => 'clients_id']);
    }
    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_clients_id' => 'clients_id']);
    }
    
    public function getHouse() {
        return $this->hasOne(Houses::className(), ['houses_client_id' => 'clients_id']);
    }    
    
    public static function findByUser($account_id) {
        $user = static::findOne(['clients_account_id' => $account_id]);
        return $user;
    }
    
    public function getId() {
        return $this->clients_id;
    }
    
    public function getFullName() {
        return $this->clients_surname . ' ' .
                $this->clients_name . ' ' .
                $this->clients_second_name;
    }
    
    public function getPhone() {
        return $this->clients_mobile;
    }
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'clients_id' => 'Clients ID',
            'clients_name' => 'Clients Name',
            'clients_second_name' => 'Clients Second Name',
            'clients_surname' => 'Clients Surname',
            'clients_mobile' => 'Clients Mobile',
            'clients_phone' => 'Clients Phone',
            'clients_account_id' => 'Clients Account ID',
            'is_rent' => 'Clients Rent',
        ];
    }
}
