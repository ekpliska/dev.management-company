<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\User;
    use yii\helpers\ArrayHelper;

/**
 * Арендатор
 */
class Rents extends ActiveRecord
{
    
    /*
     * Статусы арендатора
     * STATUS_DISABLED - арендатор не закреплен за лицевым счетом
     * STATUS_ENABLED - арендатор закреплен за лицевым счетом
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;


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
            
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_email'], 'filter', 'filter' => 'trim'],
            
//            [[
//                'rents_name', 'rents_second_name', 
//                'rents_surname'], 'match', 'pattern' => '/^[А-я-]+$/i', 'message' => 'Поле содержит не допустимые символы'
//            ],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Пользователь с введенным элетронным адресом в системе уже зарегистрирован'
            ],
            
            [['rents_name', 'rents_second_name', 'rents_surname'], 'string', 'min' => 3, 'max' => 70],
            
            [['rents_mobile'], 'string', 'max' => 50],
            ['rents_mobile', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i'],
            
            ['rents_email', 'email'],
            
            ['isActive', 'boolean'],
        ];
    }
    
    /*
     * Связь с таблицей Собственники
     */
    function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'rents_clients_id']);
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
     * После создания новой записи Арендатора производим
     * добавление роли Арендатор к учетной записи пользователя
     */
    public function afterSave($insert, $changedAttributes) {
        
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $rentRole = Yii::$app->authManager->getRole('clients_rent');
            Yii::$app->authManager->assign($rentRole, $this->getId());                    
        }
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

    public function setAccountId($account_id) {
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_id])
                ->select('account_id')
                ->asArray()
                ->one();
        $this->rents_account_id = $id['account_id'];
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
            'rents_email' => 'Электронная почта',
            'rents_account_id' => 'Rents Account ID',
        ];
    }
}
