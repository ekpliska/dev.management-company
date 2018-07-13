<?php

    namespace app\models;
    use yii\db\ActiveRecord;
    use Yii;
    use app\models\PersonalAccount;

/**
 * Жильцы, арендаторы
 */
class Lodgers extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'lodgers';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['lodger_surname', 'lodger_name', 'lodger_second_name', 'lodger_phone', 'lodger_mobile', 'lodger_email', 'lodger_account_id'], 'required'],
            [['lodger_account_id'], 'integer'],
            [['lodger_surname', 'lodger_name', 'lodger_second_name'], 'string', 'max' => 100],
            [['lodger_phone', 'lodger_mobile'], 'string', 'max' => 70],
            [['lodger_email'], 'string', 'max' => 255],
        ];
    }
    
    public function getPersonalAccount() {
        return $this->hasMany(PersonalAccount::className(), ['lodger_account_id' => 'account_id']);
    }

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'lodger_id' => 'Lodger ID',
            'lodger_surname' => 'Lodger Surname',
            'lodger_name' => 'Lodger Name',
            'lodger_second_name' => 'Lodger Second Name',
            'lodger_phone' => 'Lodger Phone',
            'lodger_mobile' => 'Lodger Mobile',
            'lodger_email' => 'Lodger Email',
            'lodger_account_id' => 'Lodger Account ID',
        ];
    }
}
