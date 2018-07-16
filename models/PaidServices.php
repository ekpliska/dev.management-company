<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\behaviors\TimestampBehavior;

/**
 * Платные услуги
 */
class PaidServices extends ActiveRecord
{
    
    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_PERFORM = 2;
    const STATUS_FEEDBAK = 3;
    const STATUS_CLOSE = 4;
    const STATUS_REJECT = 5;
    const STATUS_CONFIRM = 6;
    const STATUS_ON_VIEW = 7;    

    const SCENARIO_ADD_SERVICE = 'add_record';
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }    
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'paid_services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['services_phone', 'services_comment'], 'required', 'on' => self::SCENARIO_ADD_SERVICE],
            
            [['services_name_services_id', 'created_at', 'updated_at', 'status', 'services_dispatcher_id', 'services_specialist_id', 'services_user_id'], 'integer'],
            [['services_number'], 'string', 'max' => 16],
            [['services_comment'], 'string', 'max' => 255],
            [['services_phone'], 'string', 'max' => 50],
        ];
    }

    /*
     * Массив статусов заявок
     */
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

    /*
     * Получить название статуса по его номеру
     */
    public function getStatusName() {
        return ArrayHelper::getValue(self::getStatusNameArray(), $this->status);
    }
    
    /*
     * Сохранение новой платной заявки
     */
    public function addOrder() {
        
        if ($this->validate()) {
            $this->services_user_id = Yii::$app->user->identity->user_id;
            $this->save();
        }
    }
    
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_number' => 'Services Number',
            'services_category_id' => 'Services Category ID',
            'services_name_services_id' => 'Services Name Services ID',
            'services_comment' => 'Комментарий к заявке',
            'services_phone' => 'Ваш телефон',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'services_dispatcher_id' => 'Services Dispatcher ID',
            'services_specialist_id' => 'Services Specialist ID',
            'services_user_id' => 'Services User ID',
        ];
    }
}
