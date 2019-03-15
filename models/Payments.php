<?php

    namespace app\models;
    use Yii;
    use app\models\PersonalAccount;
    use app\models\User;

/**
 * Платежи
 */
class Payments extends \yii\db\ActiveRecord {
    
    const YES_PAID = 1;
    const NOT_PAID = 0;

    /**
     * Таблица в БД
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['unique_number', 'receipt_period', 'receipt_number', 'payment_sum', 'account_uid', 'user_uid'], 'required'],
            [['receipt_period', 'payment_status', 'account_uid', 'user_uid'], 'integer'],
            [['payment_sum'], 'number'],
            [['create_at'], 'safe'],
            [['unique_number'], 'string', 'max' => 255],
            [['receipt_number'], 'string', 'max' => 70],
            [['account_uid'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalAccount::className(), 'targetAttribute' => ['account_uid' => 'account_id']],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }

    /**
     * Связь с таблицей Лицевой счет
     */
    public function getAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'account_uid']);
    }

    /**
     * Свзяь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_uid']);
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id_payment' => 'Id Payment',
            'unique_number' => 'Unique Number',
            'receipt_period' => 'Receipt Period',
            'receipt_number' => 'Receipt Number',
            'payment_sum' => 'Payment Sum',
            'payment_status' => 'Payment Status',
            'account_uid' => 'Account Uid',
            'user_uid' => 'User Uid',
            'create_at' => 'Create At',
        ];
    }

}
