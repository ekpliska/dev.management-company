<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use app\models\TypeRequests;

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

    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_PERFORM = 2;
    const STATUS_FEEDBAK = 3;
    const STATUS_CLOSE = 4;
    const STATUS_REJECT = 5;
    const STATUS_CONFIRM = 6;
    const STATUS_ON_VIEW = 7;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requests';
    }
    
    public static function getStatusNameArray() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
            self::STATUS_REJECT => 'Отклонена',
            self::STATUS_CONFIRM => 'Подтверждена пользователем',
            self::STATUS_ON_VIEW => 'На рассмотрении',
        ];
    }
    
    public function getStatusName() {
        return ArrayHelper::getValue(self::getStatusNameArray(), $this->status);
    }

    public function getNameRequest() {
        return ArrayHelper::getValue(TypeRequests::getTypeNameArray(), $this->requests_type_id);
    }    
    
    
    public static function findRequestByIdent($request_numder) {
        return self::find()
                ->andWhere(['requests_ident' => $request_numder])
                ->one();
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
    
    public static function findByUser($user_id) {
        return self::find()
                ->andWhere(['requests_user_id' => $user_id])
                ->orderBy(['created_at' => SORT_DESC]);
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
