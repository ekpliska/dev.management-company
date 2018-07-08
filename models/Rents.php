<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\User;

/**
 * Арендатор
 */
class Rents extends ActiveRecord
{
    /**
     * {@inheritdoc}
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
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_mobile', 'rents_email'], 'required'],
            
            [
                'rents_email', 'unique',
                'targetClass' => User::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Пользователь с введенным элетронным аресом в системе уже зарегистрирован'
            ],
            
            [['rents_account_id'], 'integer'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'string', 'max' => 70],
            [['rents_mobile'], 'string', 'max' => 50],
            ['rents_email', 'email'],
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
            'rents_name' => 'Rents Name',
            'rents_second_name' => 'Rents Second Name',
            'rents_surname' => 'Rents Surname',
            'rents_mobile' => 'Rents Mobile',
            'rents_account_id' => 'Rents Account ID',
        ];
    }
}
