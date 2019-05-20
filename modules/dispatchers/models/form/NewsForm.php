<?php

    namespace app\modules\dispatchers\models\form;
    use Yii;
    use yii\base\Model;
    use app\models\News;
    use yii\helpers\HtmlPurifier;
    use app\models\TokenPushMobile;
    use app\models\SendSubscribers;

/* 
 * Создание новой новости
 */
class NewsForm extends Model {
    
    // Статус публикации
    public $status;
    // Тип рубрикм
    public $rubric;
    // Заголовок
    public $title;
    // Текс новости
    public $text;
    // Изображение новости
    public $preview;
    // Дом
    public $house;
    // Тип публикации
    public $isPrivateOffice;
    // Тип оповещение (Email, Push)
    public $isNotice;
    // Пользователь
    public $user;
    // Загружаемые файлы
    public $files;
    // Переключатель на рекламную публикацию
    public $isAdvert = false;
    // Контрагент
    public $partner;


    public function rules() {
        return [
            [[
                'status',
                'rubric', 'title', 'text', 'preview', 
                'house', 
                'isPrivateOffice', 
                'user'], 'required'],
            
            ['title', 'string', 'min' => '6', 'max' => '255'],
                        
            ['text', 'string', 'min' => '10', 'max' => '10000'],
            
            [['title', 'text'], 'filter', 'filter' => 'trim'],
            
            [['house', 'isPrivateOffice', 'isNotice', 'partner'], 'integer'],
            
            [['preview'], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg',
                'maxSize' => 10 * 1024 * 1024,
                'mimeTypes' => 'image/*',
            ],
            
            [['files'], 'file', 
                'extensions' => 'doc, docx, pdf, xls, xlsx, ppt, pptx, png, jpg, jepg', 
                'maxFiles' => 4],
            
            [['partner', 'isAdvert'], 'integer'],
            
            ['status', 'string', 'max' => 10],
            
            // Поле контрагент обязательно для заполнения, если стоит переключатель "рекламная запись"
            ['partner', 'required', 'when' => function($model) {
                return $model->isAdvert = true;
            }],            
            
        ];
    }
    
    /*
     * Сохраняем запись публикации
     * 
     * @param string $file Превью новости
     * @param string $files Прикрепленные изображения
     */
    public function save($file, $files) {
        
        $transaction = Yii::$app->db->beginTransaction();
//        var_dump(count($this->isNotice)); die();
        
        try {
            
            $add_news = new News();
            $add_news->news_type_rubric_id = $this->rubric;
            $add_news->news_title = HtmlPurifier::process(strip_tags($this->title));
            $add_news->news_text = $this->text;
            $add_news->news_text_mobile = strip_tags($this->text);
            
            // Если значение дома не задано, то публикацию сохраняем как "для всех" (null)
            $add_news->news_house_id = $this->house ? $this->house : null;
            $add_news->news_status = $this->status;
            // Пользователь, создавший публикацию
            $add_news->news_user_id = Yii::$app->user->identity->id;
            // Сохраняем превью публикации
            $add_news->uploadImage($file);
            
            // Тип оповещени1 push, email
            $count_notice = isset($this->isNotice) ? count($this->isNotice) : 0;
            if ($count_notice == 2) {
                $add_news->isPush = $this->isNotice[0] == 3 ? News::NOTICE_YES : News::NOTICE_NO;
                $add_news->isEmail = $this->isNotice[1] == 2 ? News::NOTICE_YES : News::NOTICE_NO;
            } elseif ($count_notice == 1 && $this->isNotice[0] == 3) {
                $add_news->isPush = News::NOTICE_YES;
                $add_news->isEmail = News::NOTICE_NO;
            } elseif ($count_notice == 1 && $this->isNotice[0] == 2) {
                $add_news->isPush = News::NOTICE_NO;
                $add_news->isEmail = News::NOTICE_YES;
            }
            
            if ($this->isAdvert == 1) {
                $add_news->isAdvert = 1;
                $add_news->news_partner_id = $this->partner;
            } else {
                $add_news->isAdvert = 0;
                $add_news->news_partner_id = null;
            }
            
            if(!$add_news->save()) {
                throw new \yii\db\Exception('Ошибка добавления новости. Ошибка: ' . join(', ', $add_news->getFirstErrors()));
            }
                        
            // Отправляем push-уведомления
            if ($add_news->isPush == News::NOTICE_YES) {
                $house_id = $add_news->news_house_id;
                TokenPushMobile::sendPublishNotice(TokenPushMobile::TYPE_PUBLISH_NEWS, $add_news->news_title, $house_id);
            }
            
            // Ставим публикуемую статью на очередь в рассылку
            if ($add_news->isEmail == News::NOTICE_YES) {
                SendSubscribers::createSubscriber(SendSubscribers::POST_TYPE_NEWS, $add_news->news_id, $add_news->news_house_id);
            }
            
            // Сохраняем прикрепленные изображения
            $add_news->uploadFiles($files);
            $transaction->commit();
            
            return $add_news;
            
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        
    }

    public function attributeLabels() {
        return [
            'status' => 'Статус публикации',
            'rubric' => 'Тип публикации',
            'title' => 'Заголовок публикации',
            'text' => 'Текст публикации',
            'preview' => 'Превью',
            'house' => 'Адрес',
            'isPrivateOffice' => 'Уведомления',
            'isSMS' => 'СМС',
            'isEmail' => 'Email',
            'isPush' => 'Push',
            'user' => 'Пользователь',
            'files' => 'Прикрепленные файлы',
            'isAdvert' => 'Рекламная публикация',
            'partner' => 'Котрагент',
        ];
    }
    
    
}
?>
