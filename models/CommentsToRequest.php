<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Requests;
    use yii\behaviors\TimestampBehavior;

/**
 * Комментарии к заявкам
 */
class CommentsToRequest extends ActiveRecord
{
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'comments_to_request';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            ['comments_text', 'required'],
            [['comments_request_id', 'comments_user_id', 'created_at'], 'integer'],
            [['comments_text'], 'string', 'min' => 10, 'max' => 255],
        ];
    }
    
    public function getComment() {
        return $this->hasOne(Requests::className(), ['requests_id' => 'comments_request_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'comments_user_id']);
    }    

    /*
     * Жадная выгрузка данных для формирования комментариев, соответствующих своей заявке
     */
    public static function findCommentsById($request_id) {
        return self::find()
                ->joinWith(['user', 'user.client'])
                ->andWhere(['comments_request_id' => $request_id])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();
    }
    
    /*
     * Сохранение комментария в бд
     */
    public function sendComment($request_id) {
        
        if ($this->validate()) {
            $this->comments_request_id = $request_id;
            $this->comments_user_id = Yii::$app->user->identity->user_id;
            return $this->save() ? true : null;
        }
    }

    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'comments_id' => 'Comments ID',
            'comments_request_id' => 'Comments Request ID',
            'comments_user_id' => 'Comments User ID',
            'comments_text' => 'Ваш комментарий...',
            'created_at' => 'Created At',
        ];
    }
}
