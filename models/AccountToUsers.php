<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Промежуточная таблица для связи Пользователй и Лицевого счета
 * Один пользователь может иметь несколько лицевых счетов
 * Один лицевой счет может иметь несколько пользователей (собственника и 1(+) арендатора)
 */
class AccountToUsers extends ActiveRecord
{
    /**
     * Тбалица БД
     */
    public static function tableName()
    {
        return 'account_to_users';
    }

    public function rules()
    {
        return [
            [['account_id', 'user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'user_id' => 'User ID',
        ];
    }
}
