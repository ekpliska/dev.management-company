<?php

    namespace app\models;
    use Yii;
    use yii\db\Expression;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use app\models\Questions;

/**
 * Голосование
 */
class Voting extends ActiveRecord
{
    
    // Активено
    const STATUS_ACTIVE = 0;
    // Завершено
    const STATUS_CLOSED = 1;
    // Отменено
    const STATUS_CANCEL = 2;

    const TYPE_ALL_HOUSE = 0;
    const TYPE_PORCH = 1;

    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'voting';
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression("'" . date('Y-m-d H:i:s') . "'"),
            ]
        ];
    }
    
    /**
     * Правила вадилации
     */
    public function rules()
    {
        return [
            [['voting_type', 'voting_title', 'voting_text', 'voting_object', 'voting_user_id'], 'required'],
            [['voting_type', 'voting_object', 'status', 'voting_user_id'], 'integer'],
            [['voting_text'], 'string'],
            [['voting_date_start', 'voting_date_end', 'created_at', 'updated_at'], 'safe'],
            [['voting_title', 'voting_image'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Связь с таблицей Вопросы
     */
    public function getQuestion()
    {
        return $this->hasMany(Questions::className(), ['questions_voting_id' => 'voting_id']);
    }
    
    /*
     * Тип голосования
     */
    public static function getTypeVoting() {
        return [
            self::TYPE_ALL_HOUSE => 'Для дома',
            self::TYPE_PORCH => 'Подьезд',
        ];
    }
    
    /*
     * 
     */
    public function getId() {
        return $this->voting_id;
    }
    
    /*
     * Загрузка обложки голосования
     */
    public function uploadImage($file) {
        
        $current_image = $this->voting_image;
        
        if ($this->validate()) {
            if ($file) {
                $this->voting_image = $file;
                $dir = Yii::getAlias('upload/voting/cover/');
                $file_name = 'previews_voting_' . time() . '.' . $this->voting_image->extension;
                $this->voting_image->saveAs($dir . $file_name);
                $this->voting_image = '/' . $dir . $file_name;
                @unlink(Yii::getAlias('@webroot' . $current_image));
            } else {
                $this->voting_image = $current_image;
            }
            return $this->save() ? true : false;
        }
        
        return false;
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels()
    {
        return [
            'voting_id' => 'Voiting ID',
            'voting_type' => 'Voiting Type',
            'voting_title' => 'Voiting Title',
            'voting_text' => 'Voiting Text',
            'voting_date_start' => 'Voiting Date Start',
            'voting_date_end' => 'Voiting Date End',
            'voting_object' => 'Voiting Object',
            'voting_image' => 'Voting Image',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'voting_user_id' => 'Voiting User ID',
        ];
    }

}
