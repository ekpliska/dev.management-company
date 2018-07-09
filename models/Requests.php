<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\TypeRequests;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;

/**
 * 
 */
class Requests extends ActiveRecord
{
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }    
    
    const STATUS_IN_WORK = 0;
    const STATUS_PERFORM = 1;
    const STATUS_FEEDBAK = 2;
    const STATUS_CLOSE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requests';
    }
    
    public static function getStatusNameArray() {
        return [
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'Исполненная',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
        ];
    }
    
    public function setStatusName() {
        return ArrayHelper::map(self::getStatusNameArray(), $this->status);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requests_type_id', 'requests_dispatcher_id', 'requests_specialist_id', 'created_at', 'status', 'requests_client_id', 'requests_rent_id', 'updated_at'], 'integer'],
            [['requests_comment'], 'string'],
            [['requests_ident'], 'string', 'max' => 10],
        ];
    }
    
    public function getTypeRequest() {
        return $this->hasOne(TypeRequests::className(), ['type_requests_id' => 'requests_type_id']);
    }
    
    public static function findByUser($username) {
        return self::find()
                ->andWhere(['requests_user_id' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'requests_id' => 'Requests ID',
            'requests_ident' => 'Номер заявки',
            'requests_type_id' => 'Вид заявки',
            'requests_comment' => 'Описание',
            'requests_dispatcher_id' => 'Назначена',
            'requests_specialist_id' => 'Исполнитель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата закрытия',
            'status' => 'Статус',
            'requests_client_id' => 'Requests Client ID',
            'requests_rent_id' => 'Requests Rent ID',
        ];
    }
}
