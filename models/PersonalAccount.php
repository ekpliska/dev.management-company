<?php
    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Clients;
    use app\models\Rents;

/**
 * Лицевой счет
 */
class PersonalAccount extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'personal_account';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['account_number'], 'required'],
            [['account_balance'], 'integer'],
            [['account_number'], 'string', 'max' => 100],
            [['account_organization'], 'string', 'max' => 255],
            [['account_number'], 'unique'],
        ];
    }
    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_account_id' => 'account_id']);
    }
    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_account_id' => 'account_id']);
    }

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'account_number' => 'Account Number',
            'account_organization' => 'Account Organiztion',
            'account_balance' => 'Account Square',
        ];
    }
}
