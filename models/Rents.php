<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;    
    use app\models\PersonalAccount;
    use app\models\User;
    use app\models\Clients;
    use app\models\Rents;
    use app\models\AccountToUsers;

/**
 * Арендатор
 */
class Rents extends ActiveRecord
{
    
    /*
     * Статусы арендатора
     * STATUS_DISABLED - арендатор не активен, не закреплен за лицевым счетом
     * STATUS_ENABLED - арендатор ативен, закреплен за лицевым счетом
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    
    const SCENARIO_EDIT_VALIDATION = 'required fields';

    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'rents';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_mobile'], 'required'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'filter', 'filter' => 'trim'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'string', 'min' => 3, 'max' => 50],
            [
                ['rents_name', 'rents_second_name', 'rents_surname'], 
                'match',
                'pattern' => '/^[А-Яа-я\ \-]+$/iu',
                'message' => 'Поле "{attribute}" может содержать только буквы русского алфавита, и знак "-"',
            ],
            
            [
                'rents_mobile', 'unique',
                'targetClass' => self::className(),
                'targetAttribute' => 'rents_mobile',
                'message' => 'Пользователь с введенным номером мобильного телефона в системе уже зарегистрирован',
                'on' => self::SCENARIO_EDIT_VALIDATION,
            ],
            
            [['rents_mobile', 'rents_mobile_more'], 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            ['isActive', 'boolean'],
        ];
    }
    
    /*
     * Связь с таблицей Собственники
     */
    function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'rents_clients_id']);
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['user_rent_id' => 'rents_id']);
    }
    
    /*
     * Найти собсвенника по ID
     */
    public static function findByRent($client_id) {
        return static::find()
                ->andWhere(['rents_clients_id' => $client_id])
                ->one();
    }
    
    /*
     * Сформировать массив арендаторов закрепленных за собственником, 
     * со статусом "не активен"
     */
    public static function findByClientID($client_id) {
        $_list = self::find()
                ->andWhere(['rents_clients_id' => $client_id])
                ->andWhere(['isActive' => self::STATUS_DISABLED])
                ->select(['rents_id', 'rents_surname', 'rents_name', 'rents_second_name'])
                ->asArray()
                ->all();
        
        return 
            ArrayHelper::map($_list, 'rents_id', function ($elem) {
                return $elem['rents_surname'] . ' ' . $elem['rents_name'] . ' ' . $elem['rents_second_name'];
            });
    } 
    
    /*
     * Проверям наличие активных арендаторов у Собственника
     */
    public static function isActiveRents($client_id) {
        return $active_rent = self::find()
                ->andWhere(['rents_clients_id' => $client_id, 'isActive' => self::STATUS_ENABLED])
                ->all();
    }

    /*
     * Проверям наличие не активных арендаторов у Собственника
     */
    public static function getNotActiveRents($client_id) {
        return $not_active_rent = self::find()
                ->andWhere(['rents_clients_id' => $client_id, 'isActive' => self::STATUS_DISABLED])
                ->with('user')
                ->all();
    }
    
    /*
     * Получить ID арендатора
     */
    public function getId() {
        return $this->rents_id;
    }
    
    /*
     * Полное имя (фамилия, имя, отчество)
     */
    public function getFullName() {
        return $this->rents_surname . ' ' .
                $this->rents_name . ' ' .
                $this->rents_second_name;
    }
    
//    public function setAccountId($account_id) {
//        $id = PersonalAccount::find()
//                ->andWhere(['account_number' => $account_id])
//                ->select('account_id')
//                ->asArray()
//                ->one();
//        $this->rents_account_id = $id['account_id'];
//    }
    
    /*
     * Отвязать арендатора от лицевого счета собственника
     * При этом учетная запись арендатора для входа на портал блокируется
     */
    public function undoRentWithAccount($rent, $account) {
        
        $_user = User::findOne(['user_rent_id' => $rent]);
        $_account = PersonalAccount::findOne(['account_number' => $account]);
        
        if ($_user) {
            $this->isActive = self::STATUS_DISABLED;
            
            $_user->user_login = $_user->user_login . '_block';
            $_user->status = User::STATUS_BLOCK;
            
            $_account->personal_rent_id = null;
            
            $this->save(false);
            $_user->save(false);
            $_account->save(false);
            
            $this->checkBindRentWithAccount($_user->user_id, null);
            
            Yii::$app->session->setFlash('success', 'Арендатор ' . $this->fullName . ' был отвязан от лицевого счета №' . $_account->account_number);
            
            return true;
        }
        
        Yii::$app->session->setFlash('error', 'При отвязывании лицевого счета и арендатора возникла ощшибка. Повторите операцию еще раз');
        return false;
    }
    
    /*
     * Объединить арендатора с выбранным лицевым счетом
     * В этом случае учетная запись арендатора для входа на портал становится активной
     * (Происходит смена логина арендатора, если лицевой счет объединения изменился)
     */
    public function bindRentWithAccount($rent, $account) {
        
        $_user = User::findOne(['user_rent_id' => $rent]);
        $_account = PersonalAccount::findOne(['account_number' => $account]);
        
        if ($_user && $_account) {
            $this->isActive = self::STATUS_ENABLED;
            $this->save(false);
            
            $_user->status = User::STATUS_ENABLED;
            $_user->user_login = $_account->account_number . 'r';
            $_user->save(false);
            
            $_account->personal_rent_id = $this->id;
            $_account->save(false);
            
            $this->checkBindRentWithAccount($_user->user_id, $_account->account_id);
            
            Yii::$app->session->setFlash('success', 
                    'За лицевым счетом №' . $_account->account_number . 
                    ' закреплен арендатор ' . $this->fullName . 
                    '<br />  Логин учетной записи арендатора: ' . $_user->user_login .
                    '<br />  Пароль учетной записи арендатора: остался без изменений');
            
            return true;
        }
        
        Yii::$app->session->setFlash('error', 'При объединении лицевого счета и арендатора возникла ощшибка. Повторите операцию еще раз');
        return false;
        
    }
    
    /*
     * Метод органицует установление связей 
     * через промежуточную таблицу Лицевой счет - Пользователь
     */
    public function checkBindRentWithAccount($user_id, $account_id = null) {
        
        $data_bind = AccountToUsers::findByUserID($user_id);
        
        if (empty($account_id)) {
            return $data_bind->delete() ? true : false;
        } else {
            $data_bind->account_id = $account_id;
            return $data_bind->save(false);
        }
        
    }
    
    /*
     * После удаления Арендтора из системы
     * Удалить учетную запись Арендатора
     * Снять связь между удаленным Арендатором и Лицевым счетом
     */
    public function afterDelete() {
        parent::afterDelete();
        $_user = User::findOne(['user_rent_id' => $this->rents_id]);
        $_account = PersonalAccount::findOne(['personal_rent_id' => $this->rents_id]);
        if ($_user) {
            $_user->delete();
            if ($_account) {
                $_account->personal_rent_id = null;
                $_account->save(false);
                
                Yii::$app->session->setFlash('success', 'Арендатор ' . $this->fullName . ' и его учетная запись удалены с порта');
            }
        }
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'rents_id' => 'Rents ID',
            'rents_name' => 'Имя',
            'rents_second_name' => 'Отчество',
            'rents_surname' => 'Фамилия',
            'rents_mobile' => 'Контактный телефон',
            'rents_mobile_more' => 'Дополнительный номер телефона',
        ];
    }
}
