<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;

/**
 * Токены мобильных устройств для рассылки PUSH-уведомлений
 */
class TokenPushMobile extends ActiveRecord {
    
    /**
     * Таблица БД
     */
    public static function tableName() {
        return 'token_push_mobile';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['user_uid', 'token'], 'required'],
            [['user_uid'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'user_id']],
        ];
    }
    
    /**
     * Связь с таблицей 
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_uid']);
    }
    
    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'token' => 'Token',
        ];
    }

}
