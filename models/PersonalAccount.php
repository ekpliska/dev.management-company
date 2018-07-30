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
    /* Статус лицевого счета
     * устанавливается, когда пользователь создает новый лицевой счет
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    
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
            [['account_number'], 'string', 'min' => 11, 'max' => 11],
            [['account_organization_id'], 'integer'],
            [['account_number'], 'unique'],
            
        ];
    }
    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'personal_clients_id']);
    }
    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_id' => 'personal_rent_id']);
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
                ->orderBy(['account_id' => SORT_ASC]);
                // ->one();
    }
    
    /*
     * Поиск лицевого счета по ID клиента
     * Для контроллера Лецевой счет
     * Для DataProvider
     */
    public static function findByClientID($client_id) {
        return static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->orderBy(['account_id' => SORT_ASC])
                ->limit(1);
    }

    /*
     * Поиск лицевого счета по ID клиента
     * Для контроллера Профиль пользователя
     */    
    public static function findByClientProfile($client_id) {
        return static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->orderBy(['account_id' => SORT_ASC])
                ->limit(1)
                ->one();
    }    
    
    /*
     * Получить список всех лицевых счетов закрепенных за данным пользователем
     */
    public static function findByClient($client_id) {
        
        $account_find = static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->andWhere(['isActive' => self::STATUS_ENABLED])
                ->orderBy(['account_id' => SORT_ASC])
                ->all();
        return $account_all = ArrayHelper::map($account_find, 'account_id', 'account_number');
    }
    
    public static function findByAccountNumber($user_id) {
        return static::find()
                ->andWhere(['personal_user_id' => $user_id])
                ->select('account_number')
                ->one();
    }
    
    /*
     * Поиск Арендатора закрепленного за лицевым счетом
     */
    public static function findByRent($account_id, $client_id) {
        return self::find()
                ->andWhere(['account_id' => $account_id])
                ->andWhere(['personal_clients_id' => $client_id])
                ->andWhere(['not', ['personal_rent_id' => 'null']])
                ->one();
    }
    
    /*
     * Собираем ID всех пользователей, привязанных к новому лицевому счету
     * Новым пользователем для нового лицевого счета может быть Собственник / и Арендатор
     */
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
     * Если происходит добавление нового лицевого счета, связываем талицы Пользователь и Лицевой счет через промежуточную
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
