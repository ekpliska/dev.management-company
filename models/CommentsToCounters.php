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
            [['comments_title', 'comments_text'], 'required'],
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
    
    /*
     * Получить комментарий к приборам учета, по заданному лицевому счету
     */
    public static function getComments($account_id) {
        
        return self::find()
                ->where(['account_id' => $account_id])
                ->asArray()
                ->one();
        
    }

    /**
     * Метки полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'comments_title' => 'Заголовок',
            'comments_text' => 'Комментарий',
            'account_id' => 'Лицевой счет',
            'user_id' => 'Пользоваель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

}
