<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;

/**
 * Список рубрик новостной ленты
 */
class SubBarNews extends Widget {

    public $general_navbar = [
        'important_information' => 'Важная информация',
        'special_offers' => 'Специальные предложения',
        'house_news' => 'Новости дома',
    ];
    
    public function run() {
        
        return $this->render('subbarnews/default', [
            'general_navbar' => $this->general_navbar,
        ]);
    }
        
}
