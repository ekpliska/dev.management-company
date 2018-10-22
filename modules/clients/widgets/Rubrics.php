<?php

    namespace app\modules\clients\widgets;
    use yii\base\Widget;
    use app\models\Rubrics as ModelRubrics;

/**
 * Список рубрик новостной ленты
 */
class Rubrics extends Widget {

    public function run() {
        $rubrics = ModelRubrics::getArrayRubrics();
        
        return $this->render('rubrics/default', [
            'rubrics' => $rubrics,
        ]);
    }
        
}
