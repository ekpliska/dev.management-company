<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Клиенты (собственники, арендаторы)
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
            [['clients_account_id', 'clients_keeper', 'clients_rent'], 'integer'],
            [['clients_name', 'clients_second_name', 'clients_surname'], 'string', 'max' => 70],
            [['clients_mobile', 'clients_phone'], 'string', 'max' => 50],
        ];
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
            'clients_keeper' => 'Clients Keeper',
            'clients_rent' => 'Clients Rent',
        ];
    }
}
