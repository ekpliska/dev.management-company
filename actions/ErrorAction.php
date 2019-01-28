<?php

    namespace app\actions;
    use app\modules\core\exceptions\CoreHttpException;
    use Yii;
    use yii\web\ErrorAction as BaseErrorAction;
    
class ErrorAction extends BaseErrorAction
{
    /**
     * @inheritdoc
     * @return string
     */
    public function run() {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
    
}