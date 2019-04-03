<?php
    namespace app\models;
    use Yii;
    use yii\db\Expression;
    use yii\db\ActiveRecord;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\Flats;
    use app\models\Organizations;
    use yii\helpers\ArrayHelper;
    use app\models\AccountToUsers;
    use app\models\Requests;

/**
 * Лицевой счет
 */
class PersonalAccount extends ActiveRecord
{
    /* Статус текущего лицевого счета 
     * устанавливается, когда пользователь создает новый лицевой счет
     * или переключает текущий лицевой счет в Профиле пользователя
     */
    const STATUS_CURRENT = 1;
    const STATUS_NOT_CURRENT = 0;
    
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
            ['account_number', 'required'],
            ['account_balance', 'double', 'min' => 20.00, 'max' => 1000.00],
            ['account_organization_id', 'integer'],
            [['personal_clients_id', 'personal_rent_id', 'personal_flat_id'], 'integer']
        ];
    }
    
    public function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'personal_clients_id']);
    }
    
    public function getRent() {
        return $this->hasOne(Rents::className(), ['rents_id' => 'personal_rent_id']);
    }
    
    public function getFlat() {
        return $this->hasOne(Flats::className(), ['flats_id' => 'personal_flat_id']);        
    }
    
    public function getOrganization() {
        return $this->hasOne(Organizations::className(), ['organizations_id' => 'account_organization_id']);
    }
    
    public function getRequest() {
        return $this->hasMany(Requests::className(), ['requests_account_id' => 'account_id']);
    }

    public function getPaidRequest() {
        return $this->hasMany(PaidServices::className(), ['services_account_id' => 'account_id']);
    }
    
    /*
     * Свзяь с промежуточной таблицей
     */
    public function getUser() {
        return $this->hasMany(User::className(), ['user_id' => 'user_id'])
                ->viaTable('account_to_users', ['account_id' => 'account_id']);
    }
    
    /*
     * Поиск номера лицевого счета по ID
     */
    public static function findByNumber($account_number) {
        return static::find()
                ->joinWith(['organization', 'client', 'flat', 'flat.house', 'rent'])
                ->andWhere(['account_number' => $account_number])
                ->one();
    }
    
    /*
     * проверить сущестование лицевого счета
     *      по Номеру лицевого счета
     *      по Последней сумме в квитанции
     *      по Прощади жилого повещения
     */
    public static function findAccountBeforeRegister($account) {
        
        $is_account = self::find()
                ->select(['account_number', 'account_balance', 'flats_square', 'personal_flat_id'])
                ->joinWith('flat')
                ->where(['account_number' => $account])
                ->asArray()
                ->one();
        return !empty($is_account) ? true : false;
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
     * Поиск по ID квартиры
     */
    public static function findByFlatId($flat_id) {
        return self::find()
                ->where(['personal_flat_id' => $flat_id])
                ->asArray()
                ->one();
    }

    /*
     * Информация по лицевому счету
     * 
     * @param array $info Информация по текущему лицевому счету/собственник/жилой массив
     */
    public static function getAccountInfo($account_id, $client_id) {
        
        if (Yii::$app->user->can('clients')) {
            $_where = ['personal_clients_id' => $client_id];
        } else if (Yii::$app->user->can('clients_rent')) {
            $_where = ['personal_rent_id' => $client_id];            
        }
        
        $info = (new \yii\db\Query)
                ->from('personal_account')
                ->join('LEFT JOIN', 'clients', 'personal_clients_id = clients_id')
                ->join('LEFT JOIN', 'flats', 'flats_id = personal_flat_id')
                ->join('LEFT JOIN', 'houses', 'flats_house_id = houses_id')
                ->join('LEFT JOIN', 'rents', 'personal_rent_id = rents_id')
                ->join('LEFT JOIN', 'organizations', 'account_organization_id = organizations_id')
                ->where(['account_id' => $account_id])
                ->andWhere($_where)
                ->one();
        
        return $info;
        
    }
    
    
    /*
     * Поиск лицевого счета по ID
     */
    public static function findByAccountID($account_id) {
        return static::find()
                ->andWhere(['account_id' => $account_id])
                ->one();
    }
    
    /*
     * Получить список всех лицевых счетов закрепленны за данным пользователем
     * @param boolean $all
     * 
     *      $all == true Получить все лицевые счета собственника
     *      $all == false Получить все лицевые счета не связанные с Арендатором
     * 
     */
    public static function findByClient($client_id, $all) {
        
        if ($all) {
            $account_find = static::find()
                    ->andWhere(['personal_clients_id' => $client_id])
                    ->orderBy(['account_id' => SORT_ASC])
                    ->all();
        } else {
            $account_find = static::find()
                ->andWhere(['personal_clients_id' => $client_id])
                ->andwhere(['IS', 'personal_rent_id', (new Expression('Null'))])
                ->orderBy(['account_id' => SORT_ASC])
                ->all();
        }
        return $account_all = ArrayHelper::map($account_find, 'account_id', 'account_number');
    }
    
    /*
     * Получить список лицевых счетов по ID пользователя
     * 
     * joinWith('user') Связь через промежуточную таблицу Лицевой счет - Пользователь 
     */
    public static function getListAccountByUserID($user_id) {
        
        $result = self::find()
                ->joinWith('user', false)
                ->where(['user.user_id' => $user_id])
                ->asArray()
                ->orderBy(['isActive' => SORT_DESC])
                ->all();
        
        $lists = [];
        
        foreach ($result as $key => $account) {
            $list = [
                'id' => $account['account_id'],
                'number' => $account['account_number'],
            ];
            $lists[] = $list;
        }
        
        return [
            'lists' => ArrayHelper::map($result, 'account_id', 'account_number'),
            'current_account' => $lists[0],
        ];
        
    }

    /*
     * Поиск Арендатора закрепленного за лицевым счетом
     * по ID лицевого счета
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
    public function setUserList($client_id = null, $rent_id) {
        
       $client_id ? $_list_client = ArrayHelper::map(User::find()
               ->andWhere(['user_client_id' => $client_id])
               ->asArray()
               ->all(), 'user_id', 'user_client_id') : $_list_client = [];

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
                $bind_date->save(false);
            } 
            
        } else {
            $this->setUserList(null, $this->personal_rent_id);
            foreach ($this->_list_user as $key => $user) {
                $bind_date = new AccountToUsers();
                $bind_date->user_id = $key;
                $bind_date->account_id = $this->account_id;
                $bind_date->save();
            }
        }
    }
    
    /*
     * Смена текущего лицевого счета
     */
    public static function changeCurrentAccount($current_account_id, $new_current_account_id) {
        
        $old_choosing = PersonalAccount::findByAccountID($current_account_id);
        $old_choosing->isActive = self::STATUS_NOT_CURRENT;
        $old_choosing->save(false);
        
        $new_choosing = PersonalAccount::findByAccountID($new_current_account_id);
        $new_choosing->isActive = self::STATUS_CURRENT;
        $new_choosing->save(false);
        
        return true;
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
