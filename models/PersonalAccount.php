<?php
    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\Houses;
    use app\models\Organizations;
    use yii\helpers\ArrayHelper;
    use app\models\AccountToUsers;

/**
 * Лицевой счет
 */
class PersonalAccount extends ActiveRecord
{
    
    public $_list_user = [];
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
            [['account_balance'], 'string'],
            // [['account_number'], 'string', 'min' => 11, 'max' => 11],
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
    
    /*
     * Свзяь с промежуточной таблицей
     */
    public function getUser() {
        return $this->hasMany(User::className(), ['user_id' => 'user_id'])
                ->viaTable('account_to_users', ['account_id' => 'account_id']);
    }
    
    public static function findByNumber($account_id) {
        return static::find()
                ->andWhere(['account_id' => $account_id])
                ->orderBy(['account_id' => SORT_DESC]);
                // ->one();
    }
    
    public static function findByClientID($client_id) {
        return static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->orderBy(['account_id' => SORT_DESC])
                ->limit(1);
    }
    
    /*
     * Получить список всех лицевых счетов закрепенных за данным пользователем
     */
    public static function findByClient($client_id) {
        
        $account_find = static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->all();
        return $account_all = ArrayHelper::map($account_find, 'account_id', 'account_number');
    }
    
    public static function findByAccountNumber($user_id) {
        return static::find()
                ->andWhere(['personal_user_id' => $user_id])
                ->select('account_number')
                ->one();
    }
    
    public function setUserList($client_id, $rent_id) {
       $_list_client = ArrayHelper::map(User::find()
               ->andWhere(['user_client_id' => $client_id])
               ->asArray()
               ->all(), 'user_id', 'user_client_id');

       $rent_id ? $_list_rent = ArrayHelper::map(User::find()
               ->andWhere(['user_rent_id' => $rent_id])
               ->asArray()
               ->all(), 'user_id', 'user_rent_id') : $_list_rent = [];
       
       $this->_list_user = ArrayHelper::merge($_list_client, $_list_rent);
       
    }
    
    /*
     * Этот метод вызывается в конце вставки или обновления записи
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->setUserList($this->personal_clients_id, $this->personal_rent_id);
            foreach ($this->_list_user as $key => $user) {
                $bind_date = new AccountToUsers();
                $bind_date->user_id = $key;
                $bind_date->account_id = $this->account_id;
                $bind_date->save();
            }
        }
//            foreach ($this->getUserList($this->personal_clients_id, $this->personal_rent_id) as $user) {
//                $_t = new AccountToUsers();
//                $_t->isNewRecord = true;
//                $_t->user_id = $user;
//                $_t->account_id = $this->account_id;
//                $_t->save(false);
//            }
//            echo '<pre>'; var_dump($this->getUserList($this->personal_clients_id, $this->personal_rent_id));
//            echo '<ht />';
//            die;
//        }
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
