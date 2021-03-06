<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\behaviors\TimestampBehavior;
    use yii\helpers\Html;
    use yii\imagine\Image as ImageImagine;
    use Imagine\Image\Box;
    use app\models\User;
    use app\models\TypeRequests;
    use app\models\CommentsToRequest;
    use app\models\StatusRequest;
    use app\models\Image;
    use app\models\Employees;
    use app\models\PersonalAccount;
    use app\models\Notifications;

/**
 * Заяки
 */
class Requests extends ActiveRecord
{

    const SCENARIO_ADD_REQUEST = 'add_record';
    const SCENARIO_EDIT_REQUEST = 'edit_record';
    
    /*
     * Статус принятия заявки
     */
    const ACCEPT_YES = 1;
    const ACCEPT_NO = 0;

    /*
     * Статус отключения чата
     * При создании заявки чат открыт
     * Отключение статуса доступно только для заявок со статусом Закрыто/Отклонено
     */
    const CHAT_CLOSE = 1;
    const CHAT_OPEN = 0;
    
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
            
            [['requests_type_id', 'requests_comment'], 'required', 'on' => self::SCENARIO_ADD_REQUEST],
            
            [['gallery'], 'file', 
                'extensions' => 'png, jpg, jpeg', 
                'maxFiles' => 5, 
                'maxSize' => 20 * 1024 * 1024,
                'mimeTypes' => 'image/*',                
                'on' => self::SCENARIO_ADD_REQUEST,
            ],
            [['requests_comment'], 'string', 'on' => [self::SCENARIO_ADD_REQUEST, self::SCENARIO_EDIT_REQUEST]],
            [['requests_comment'], 'string', 'max' => 1000, 'on' => [self::SCENARIO_ADD_REQUEST, self::SCENARIO_EDIT_REQUEST]],
            
            [['requests_grade', 'close_chat'], 'integer'],
            
            [['requests_type_id', 'requests_comment', 'requests_phone', 'requests_account_id'], 'required', 'on' => self::SCENARIO_EDIT_REQUEST],
            
            // Проверка на существования номера телефона, используемого в завке
            [
                'requests_phone', 'exist', 
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер в системе не найден',
                'on' => self::SCENARIO_EDIT_REQUEST,
            ],
            
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
     * Связь с таблицей прикрепленных фотографий
     */
    public function getImage() {
        return $this->hasMany(Image::className(), ['itemId' => 'requests_id'])->andWhere(['modelName' => 'Requests']);
    }
    
    /*
     * Связь с таблицей сотрудники (Специалисты)
     */
    public function getEmployeeSpecialist() {
        return $this->hasOne(Employees::className(), ['employee_id' => 'requests_specialist_id']);
    }

    /*
     * Связь с таблицей сотрудники (Диспетчеры)
     */
    public function getEmployeeDispatcher() {
        return $this->hasOne(Employees::className(), ['employee_id' => 'requests_dispatcher_id']);
    }
    
    /*
     * Связь с таблицей Лицевой счет
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'requests_account_id']);
    }
        
    /*
     * Сохранение новой заявки
     * 
     * @param integer $accoint_id ID заявки
     */
    public function addRequest($account_id) {
        
        if (!is_numeric($account_id)) {
            Yii::$app->session->setFlash('request', [
                'success' => false,
                'error' => 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз',
            ]);
            return false;
        }
        
        /* Формирование идентификатора для заявки:
         *      последние 6 символов даты в unix - 
         *      тип заявки
         */
        
        $date = new \DateTime();
        $int = $date->getTimestamp();
        
        $request_numder = substr($int, 5) . '-' . str_pad($this->requests_type_id, 2, 0, STR_PAD_LEFT);      
        
        if ($this->validate()) {
            $this->requests_ident = $request_numder;
            $this->requests_account_id = $account_id;
            $this->requests_phone = Yii::$app->userProfile->mobile;
            $this->status = StatusRequest::STATUS_NEW;
            $this->is_accept = false;
            Yii::$app->session->setFlash('request', [
                'success' => true,
                'message' => 'Ваша заявка была успешно  сформирована.' . '<br />'
                    . 'Номер вашей заявки №' . $request_numder . '<br />'
                    . 'Ознакомиться с деталями заявки можно пройдя по ' . Html::a('ссылке', ['requests/view-request', 'request_numder' => $request_numder])
            ]);
            return $this->save() ? $request_numder : false;
        }
        Yii::$app->session->setFlash('request', [
                'success' => false,
                'error' => 'При формировании заявки возникла ошибка. Обновите страницу и повторите действия еще раз',
        ]);
        return false;
    }
    
    /*
     * Добавление оценки для заявки
     * 
     * @param integer $score Оценка
     */
    public function addGrade($score) {

        if (!is_numeric($score)) {
            return false;
        }
        
        $this->requests_grade = $score;
        
        return $this->save(false) ? true : false;
        
    }
    
    /*
     * Получить тип заявки по ID
     */
    public function getNameRequest() {
        return ArrayHelper::getValue(TypeRequests::getTypeNameArray(), $this->requests_type_id);
    }
    
    /*
     * Поиск заявки по ID
     */
    public static function findByID($request_id) {
        return self::find()
                ->andWhere(['requests_id' => $request_id])
                ->one();
    }
    
    /*
     * Получить подробную информацию о заявке
     */
    public static function getFullInfoRequest($request_id) {
        
        return self::find()
                ->with(['personalAccount', 'personalAccount.client.user'])
                ->where(['requests_id' => $request_id])
                ->one();
    
    }


    /*
     * Поиск заявки по его уникальному номеру и Лицевому счету
     */
    public static function findRequestByIdent($request_numder, $account_id) {
        
        $request = (new \yii\db\Query)
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'r.requests_type_id = tr.type_requests_id')                
                ->where(['requests_ident' => $request_numder])
                ->andWhere(['requests_account_id' => $account_id])
                ->one();
        
        return $request;
        
    }
    
    /*
     * Поиск заявки по его уникальному номеру
     */
    public static function findRequestToIdent($request_numder) {

        $request = (new \yii\db\Query)
                ->select('r.requests_id as requests_id, '
                        . 'r.requests_ident as requests_ident, '
                        . 'r.is_accept as is_accept, r.status as status, '
                        . 'r.created_at as created_at, r.updated_at as updated_at, '
                        . 'r.requests_grade as requests_grade, '
                        . 'r.requests_phone as requests_phone, '
                        . 'r.requests_comment as requests_comment, '
                        . 'r.close_chat as close_chat, '
                        . 'tr.type_requests_name as type_requests_name, '
                        . 'c.clients_surname as clients_surname, c.clients_second_name as clients_second_name, c.clients_name as clients_name, '
                        . 'h.houses_gis_adress as houses_gis_adress, h.houses_number as houses_number, '
                        . 'h.houses_street as houses_street, '
                        . 'f.flats_porch as flats_porch, f.flats_floor as flats_floor, f.flats_number as flats_number, '
                        . 'ed.employee_id as employee_id_d, ed.employee_surname as surname_d, ed.employee_name as name_d, ed.employee_second_name as sname_d, '
                        . 'es.employee_id as employee_id_s, es.employee_surname as surname_s, es.employee_name as name_s, es.employee_second_name as sname_s')
                ->from('requests as r')
                ->join('LEFT JOIN', 'type_requests as tr', 'r.requests_type_id = tr.type_requests_id')                
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = r.requests_account_id')                
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')                
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'employees as ed', 'ed.employee_id = r.requests_dispatcher_id')
                ->join('LEFT JOIN', 'employees as es', 'es.employee_id = r.requests_specialist_id')
                ->join('LEFT JOIN', 'clients as c', 'c.clients_id = pa.personal_clients_id')
                ->where(['requests_ident' => $request_numder])
                ->one();
        
        return $request;
        
    }
    
     /*
     * Поиск заявки по ID лицевого счета
     * @param ActiveQuery
     */
    public static function findByAccountID($account_id) {
        
        $requests = self::find()
                ->with(['image', 'employeeSpecialist'])
                ->andWhere(['requests_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        return $requests;
    }

    /*
     * Загрузка прикрепленных к заявке изображений
     */
    public function uploadGallery() {
        
        if ($this->validate()) {
            foreach ($this->gallery as $file) {
                
                $path = 'upload/store' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $photo_path = Yii::getAlias('@webroot') . '/' . $path;
                $photo = ImageImagine::getImagine()->open($photo_path);
                $photo->thumbnail(new Box(900, 900))->save($photo_path, ['quality' => 90]);
                $this->attachImage($photo_path);
                
                @unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Переключение статусов для заявок
     */
    public function switchStatus($status) {
        
        $this->status = $status;
        $this->is_accept = true;
        
        if ($status == StatusRequest::STATUS_CLOSE || $status == StatusRequest::STATUS_REJECT) {
            $this->date_closed = time();
        } else {
            $this->date_closed = null;
            $this->requests_grade = null;
            $this->close_chat = Requests::CHAT_OPEN;
            $request_answers = RequestAnswers::deleteAll(['anwswer_request_id' => $this->requests_id]);
        }
        
        /*
         * Формируем уведомление и 
         * Отправка PUSH-уведомления
         */
        if (empty($this->status != $status)) {
            Notifications::createNoticeStatus(Notifications::TYPE_CHANGE_STATUS_IN_REQUEST, $this->requests_id, $status);
        }
        
        return $this->save(false) ? true : false;
        
    }
    
    /*
     * Установка статуса и автоматическое назначение диспетчера для текущей заявки
     */
    public function setSatusRequest($status) {

        $this->status = $status;
        $this->is_accept = true;
        $this->requests_dispatcher_id = Yii::$app->profileDispatcher->employeeID;
        
        return $this->save(false) ? true : false;

        
    }
    
    /*
     * @param integer ID заявки
     */
    public function getId() {
        return $this->requests_id;
    }
    
    /*
     * @param integet ID Лицевого счета заявки
     */
    public function getAccount() {
        return $this->requests_account_id;
    }
    
    /*
     * Получить список новых заявок, не рассмотренных в течении 10 минут
     */
    public static function getMissedListRequest() {
        
        $count_request = Requests::find()
                ->where(['AND', 
                        ['>', new \yii\db\Expression("(UNIX_TIMESTAMP(NOW()) - created_at)/60"), '10'],
                        ['status' => StatusRequest::STATUS_NEW]])
                ->limit(3)
                ->all();
        
        $array_list = ArrayHelper::map($count_request, 'requests_id', 'requests_ident');
        return $array_list ? Notifications::createNoticeMisses(Notifications::TYPE_HAVE_MISSED_REQUEST, $array_list) : false;
        
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
            'requests_grade' => 'Оценка',
        ];
    }
}
