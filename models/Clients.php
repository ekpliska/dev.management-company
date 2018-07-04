<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

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
    
    public static function findByUser($account_id) {
        $user = static::findOne(['clients_account_id' => $account_id]);
        return $user;
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
