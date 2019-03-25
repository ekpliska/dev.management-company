<?php

        namespace app\models;
        use Yii;
        use yii\db\ActiveRecord;

/**
 * Чат голосования
 */
class ChatToVote extends ActiveRecord {
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'chat_to_vote';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['vote_vid', 'uid_user', 'chat_message'], 'required'],
            [['vote_vid', 'uid_user'], 'integer'],
            [['chat_message'], 'string'],
            [['created_at'], 'safe'],
            [['uid_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid_user' => 'user_id']],
            [['vote_vid'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::className(), 'targetAttribute' => ['vote_vid' => 'voting_id']],
        ];
    }
    
    /**
     * Связь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'uid_user']);
    }

    /**
     * Связь с таблицей Опрос
     */
    public function getVote() {
        return $this->hasOne(Voting::className(), ['voting_id' => 'vote_vid']);
    }
    
    /*
     * Получить все сообщения по текущему Опросу
     */
    public static function getChat($vote_id) {
        
        $chat = self::find()
                ->with(['user', 'user.client', 'vote'])
                ->where(['vote_vid' => $vote_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        
        return $chat;
        
    }

    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'vote_vid' => 'Vote Vid',
            'uid_user' => 'Uid User',
            'chat_message' => 'Chat Message',
            'created_at' => 'Created At',
        ];
    }

    
}
