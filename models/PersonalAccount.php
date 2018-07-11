<?php
    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\Houses;
    use app\models\Organizations;

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
            [['account_number'], 'string', 'min' => 11, 'max' => 11],
            [['account_organization_id'], 'integer'],
            [['account_number'], 'unique'],
        ];
    }
    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'personal_clients_id']);
    }
    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_account_id' => 'account_id']);
    }
    
    public function getHouse() {
        return $this->hasOne(Houses::className(), ['houses_account_id' => 'account_id']);        
    }
    
    public function getOrganization() {
        return $this->hasOne(Organizations::className(), ['organizations_id' => 'account_organization_id']);
    }
    
    public static function findByClient($client_id) {
        return static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->one();
    }
    
    public static function findByAccountNumber($user_id) {
        return static::find()
                ->andWhere(['personal_user_id' => $user_id])
                ->select('account_number')
                ->one();
    }
    
    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'account_number' => 'Account Number',
            'account_organization_id' => 'Account Organiztion',
            'account_balance' => 'Account Square',
        ];
    }
}
