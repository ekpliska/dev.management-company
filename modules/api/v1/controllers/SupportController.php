<?php

    namespace app\modules\api\v1\controllers;
    use yii\rest\Controller;

/**
 * Часто задаваемые вопросы
 */
class SupportController extends Controller {
    
    public function verbs() {
        
        return [
            'index' => ['get'],
        ];
    } 
    
    public function actionIndex() {
        
        $questions_list = [
            [
                'question' => 'Английский перевод 1914 года, H. Rackham',
                'answer' => 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born '
                            . 'and I will give you a complete account of the system, and expound the actual teachings of the great explorer '
                            . 'of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because '
                            . 'it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that '
                            . 'are extremely painful.',
            ],
            [
                'question' => 'Классический текст Lorem Ipsum, используемый с XVI века',
                'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna '
                            . 'aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. '
                            . 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. '
                            . 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            ],
            [
                'question' => 'Классический текст Lorem Ipsum, используемый с XVI века',
                'answer' => 'Есть много вариантов Lorem Ipsum, но большинство из них имеет не всегда приемлемые модификации, например, юмористические '
                            . 'вставки или слова, которые даже отдалённо не напоминают латынь.',
            ],
        ];
        
        return [
            'questions_list' => $questions_list,
        ];
        
    }
    
}
