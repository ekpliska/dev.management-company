<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\PersonalAccount;

/**
 * Комментарии к приборам учета, по конкретному лицевому счету
 */
class CommentsToCounters extends ActiveRecord
{
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'comments_to_counters';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['comments_title', 'comments_text', 'account_id', 'user_id'], 'required'],
            [['comments_text'], 'string'],
            [['account_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['comments_title'], 'string', 'max' => 255],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }
    
    /**
     * Свзяь с таблицей "Лицевой счет"
     */
    public function getAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'account_id']);
    }    

    /**
     * Метки полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'comments_title' => 'Comments Title',
            'comments_text' => 'Comments Text',
            'account_id' => 'Account ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
