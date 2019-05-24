<?php

    namespace app\modules\api\v1\models\request;
    use Yii;
    use yii\base\Model;
    use yii\behaviors\TimestampBehavior;
    use app\models\Image;
    use app\models\TypeRequests;
    use app\models\Requests;
    use app\models\PersonalAccount;

/**
 * Добавление заявки
 */
class RequestForm extends Model {

    const DIR_NAME = 'upload/store/Requests';
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public $account;
    public $type_request;
    public $request_body;
    public $gallery = [];

    /*
     * Правила валидации
     */
    public function rules() {
        return [
            [['account', 'type_request', 'request_body'], 'required'],
            ['account', 'integer'],
            ['type_request', 'string', 'max' => 70],
            ['request_body', 'string', 'max' => 255],
            ['gallery', 'safe'],
            
        ];
    }
    
    /*
     * Сохранение созданной заявки
     */
    public function save() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            $type_request_name = TypeRequests::find()
                    ->where(['=', 'type_requests_name', $this->type_request])
                    ->one();
            
            $account_info = PersonalAccount::findOne(['account_number' => $this->account]);
            
            if (empty($type_request_name) || empty($account_info)) {
                return false;
            }
            
            $add_request = new Requests();
            
            $date = new \DateTime();
            $int = $date->getTimestamp();
            $request_numder = substr($int, 5) . '-' . str_pad($type_request_name->type_requests_id, 2, 0, STR_PAD_LEFT); 
            
            $add_request->requests_ident = $request_numder;
            $add_request->requests_type_id = $type_request_name->type_requests_id;
            $add_request->requests_comment = $this->request_body;
            $add_request->requests_phone = Yii::$app->user->identity->user_mobile;
            $add_request->requests_account_id = $account_info->account_id;

            if(!$add_request->save()) {
                return false;
            }
            
            // Сохраняем пришедшие изображения 
            if (!empty($this->gallery) && !$this->uploadImage($this->gallery, $add_request->requests_id)) {
                return false;
            }
                
            $transaction->commit();
            
            return true;
                
        } catch (Exception $ex) {
            $transaction->rollBack();
            // $ex->getMessage();
        }
        
    }
    
    /*
     * Загрузка на сервер вложений к заявке
     */
    private function uploadImage($images_srt, $request_id) {
        
        // Количество загружаемых файлов 5
        if (count($images_srt) > 5 ) {
            return false;
        }

        // Создаем директорию, для сохранения вложений
        $folder_path = self::DIR_NAME . "/Requests{$request_id}/";
        if (!file_exists($folder_path)) { 
            mkdir($folder_path, 0777);
        }
        
        foreach ($images_srt as $key => $image) {
            // Конвертируем пришедший файл из base64
            $data = base64_decode($image); 
            // Создание нового изображения из потока представленного строкой
            $source_img = imagecreatefromstring($data);
            // Поворот изображения с заданным углом
            $rotated_img = imagerotate($source_img, 0, 0);
            
            // Задаем уникальное имя для загруженного файла
            $file_name = uniqid(). '.png';
            $file_path = $folder_path . $file_name;
            
            // Записываем в БД пути загруженных вложений
            $image = new Image();
            $image->filePath = "Requests/Requests{$request_id}/{$file_name}";
            $image->itemId = $request_id;
            $image->modelName = 'Requests';
            $image->save(false);
            
            // Записываем изображение в файл
            $imageSave = imagejpeg($rotated_img, $file_path, 70);
            imagedestroy($source_img);
        }
        
        return true;
        
    }
    
}
