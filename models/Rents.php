<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;
    use app\models\Clients;
    use app\models\User;

/**
 * This is the model class for table "rents".
 *
 * @property int $rents_id
 * @property string $rents_name
 * @property string $rents_second_name
 * @property string $rents_surname
 * @property string $rents_mobile
 * @property int $rents_account_id
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_mobile', 'rents_email'], 'required'],
            [['rents_account_id'], 'integer'],
            [['rents_name', 'rents_second_name', 'rents_surname'], 'string', 'max' => 70],
            [['rents_mobile'], 'string', 'max' => 50],
            ['rents_email', 'email'],
        ];
    }
    
    function getClient() {
        return $this->hasOne(Clients::className(), ['clients_id' => 'rents_clients_id']);
    }
    
    public static function findByClient($clients_id) {
        return static::find()
                ->andWhere(['rents_clients_id' => $clients_id])
                ->with('client')
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
    
    public function setAccountId($account_id) {
        $id = PersonalAccount::find()
                ->andWhere(['account_number' => $account_id])
                ->select('account_id')
                ->asArray()
                ->one();
        $this->rents_account_id = $id['account_id'];
    }    

    /**
     * {@inheritdoc}
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
