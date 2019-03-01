<?php

    namespace app\widgets;
    use yii\base\Widget;
    use app\models\SliderSettings;
    

/**
 * Слайдер на главной странце портала
 */
class Slider extends Widget {
    
    public $sliders;


    public function init() {
        
        parent::init();
        
        $this->sliders = SliderSettings::find()
                ->where(['is_show' => SliderSettings::STATUS_SHOW])
                ->asArray()
                ->all();
        
    }
    
    public function run() {
        
        return $this->render('slider/default', [
            'sliders' => $this->sliders,
            'count_slider' => count($this->sliders),
        ]);
    }
    
}
