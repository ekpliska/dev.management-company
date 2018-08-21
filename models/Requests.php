<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use yii\helpers\Html;
    use app\models\TypeRequests;
    use app\models\CommentsToRequest;

/**
 * Заяки
 */
class Requests extends ActiveRecord
{

    const STATUS_NEW = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_PERFORM = 2;
    const STATUS_FEEDBAK = 3;
    const STATUS_CLOSE = 4;
    const STATUS_REJECT = 5;
    const STATUS_CONFIRM = 6;
    const STATUS_ON_VIEW = 7;
    
    const SCENARIO_ADD_REQUEST = 'add_record';
    
    // Для загружаемых файлов
    public $gallery;

    
    public function behaviors() {
        return [
            
            TimestampBehavior::className(),
            
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],            
        ];
    }
    
    /**
     * Таблица БД
     */
    public static function tableName()
    {
        return 'requests';
    }
    
    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['requests_type_id', 'requests_comment', 'requests_phone'], 'required', 'on' => self::SCENARIO_ADD_REQUEST],
            
            [
                'requests_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
                'on' => self::SCENARIO_ADD_REQUEST,
            ],
            
            [['gallery'], 'file', 
                'extensions' => 'png, jpg, jpeg', 
                'maxFiles' => 4, 
                'maxSize' => 256 * 1024,
                'mimeTypes' => 'image/*',                
                'on' => self::SCENARIO_ADD_REQUEST,
            ],
            [['requests_comment'], 'string', 'on' => self::SCENARIO_ADD_REQUEST],
            [['requests_ident'], 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_ADD_REQUEST],
            
        ];
    }
    
    /*
     * Массив всех статусов заявок
     */
    public static function getStatusNameArray() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
            self::STATUS_REJECT => 'Отклонено',
            self::STATUS_CONFIRM => 'Подтверждено пользователем',
            self::STATUS_ON_VIEW => 'На рассмотрении',
        ];
    }
    
    /*
     * Массив статусов заявок для пользователя
     */
    public static function getUserStatusRequests() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_WORK => 'В работе',
            self::STATUS_PERFORM => 'На исполнении',
            self::STATUS_FEEDBAK => 'На уточнении',
            self::STATUS_CLOSE => 'Закрыто',
        ];       
    }
    
    /*
     * Связь с талиблицей Виды заявок
     */
    public function getTypeRequest() {
        return $this->hasOne(TypeRequests::className(), ['type_requests_id' => 'requests_type_id']);
    }       
    
    /*
     * Связь с таблицей комментариев к заявке
     */
    public function getComment() {
        return $this->hasMany(CommentsToRequest::className(), ['comments_request_id' => 'requests_id']);
    }
    
    /*
     * Сохранение новой заявки
     */
    public function addRequest($accoint_id) {
        
        if (!is_numeric($accoint_id)) {
            Yii::$app->session->setFlash('error', 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз');
            return false;
        }
        
        $account = PersonalAccount::findByAccountID($accoint_id);
        
        /* Формирование идентификатора для заявки:
         *      последние 7 символов лицевого счета - 
         *      последние 6 символов даты в unix - 
         *      тип заявки
         */
        
        $date = new \DateTime();
        $int = $date->getTimestamp();
        
        $request_numder = substr($account->account_number, 4) . '-' . substr($int, 5) . '-' . str_pad($this->requests_type_id, 2, 0, STR_PAD_LEFT);      
        
        if ($this->validate()) {
            $this->requests_ident = $request_numder;
            $this->requests_account_id = $accoint_id;
            $this->status = Requests::STATUS_NEW;
            $this->is_accept = false;
            Yii::$app->session->setFlash('success', 'Ваша заявка была успешно  сформирована на лицевой счет №' . $account->account_number . '<br />'
                    . 'Номер вашей заявки №' . $request_numder . '<br />'
                    . 'Ознакомиться с деталями заявки можно пройдя по ' . Html::a('ссылке', ['requests/view-request', 'request_numder' => $request_numder]));
            return $this->save() ? true : false;
        }
        Yii::$app->session->setFlash('error', 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз');
        return false;
    }    
    
    /*
     * Получить название статуса по его номеру
     */
    public function getStatusName() {
        return ArrayHelper::getValue(self::getStatusNameArray(), $this->status);
    }

    /*
     * Получить тип заявки по ID
     */
    public function getNameRequest() {
        return ArrayHelper::getValue(TypeRequests::getTypeNameArray(), $this->requests_type_id);
    }
    
    /*
     * Поиск заявки по его уникальному номеру
     */
    public static function findRequestByIdent($request_numder) {
        return self::find()
                ->andWhere(['requests_ident' => $request_numder])
                ->one();
    }

    /*
     * Поиск заявки по ID лицевого счета
     * @param ActiveQuery
     */
    public static function findByAccountID($account_id) {
        
        return self::find()
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
    }

    /*
     * Загрузка прикрепленных к заявке изображений
     */
    public function uploadGallery() {
        
        if ($this->validate()) {
            foreach ($this->gallery as $file) {
                $path = 'upload/store' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $this->attachImage($path);
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }    
    
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'requests_id' => 'Requests ID',
            'requests_ident' => 'Номер заявки',
            'requests_type_id' => 'Вид заявки',
            'requests_comment' => 'Описание',
            'requests_dispatcher_id' => 'Назначена',
            'requests_specialist_id' => 'Исполнитель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата закрытия',
            'status' => 'Статус',
            'requests_account_id' => 'Номер лицевого счета',
            'requests_phone' => 'Контактный телефон',
            'gallery' => 'Прикрепить файлы',
            'is_accept' => 'Принято на рассмотрение',
        ];
    }
}
