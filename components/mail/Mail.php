<?php

    namespace app\components\mail;
    use Yii;

/*
 * Отправка email
 * @param string $to Email получателя
 * @param string $subject Тема письма
 * 
 * @param string $view Вид письма
 */    
class Mail {
    
    public static function send($to, $subject, $view, $file_name = null, $params = []) {
        
        $from = [Yii::$app->params['company-email'] => Yii::$app->params['company-name']];
        
        if (empty($file_name)) {
            Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                    ->setTo($to)
                    ->setFrom($from)
                    ->setSubject($subject)
                    ->send();            
        } else {
            Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                    ->setTo($to)
                    ->setFrom($from)
                    ->setSubject($subject)
                    ->attach($file_name)
                    ->send();
        }
        
    }
    
}
