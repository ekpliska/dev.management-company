<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Токен, для работы авторизации по API
 */
class Token extends ActiveRecord {
    /**
     * Таблица в БД
     */
    public static function tableName() {
        return 'token';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['user_uid', 'token', 'expired_at'], 'required'],
            [['user_uid', 'expired_at'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }
    
    /**
     * Связь с таблицей Пользоватети
     */
    public function getUserU() {
        return $this->hasOne(User::className(), ['user_id' => 'user_uid']);
    }
    
    /*
     * Метод генерации токена, который будет формирует время когда токен будет истечь
     * и генерирует сам токен
     */
    public function generateToken($expired) {
        
        $this->expired_at = $expired;
        $this->token = Yii::$app->security->generateRandomString();
        
    }

    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'token' => 'Token',
            'expired_at' => 'Expired At',
        ];
    }

}
