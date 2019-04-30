<?php

    namespace app\modules\dispatchers\widgets;
    use yii\base\Widget;
    use yii\web\NotFoundHttpException;
    use app\models\StatusRequest;
    use app\models\Requests;
    use app\models\PaidServices;

/**
 * Блок Оперативной информации по заявкам, Отчеты
 */
class ReportBar extends Widget {
    
    // Тип, заявки, платные услуги
    public $type = 'requests';
    // Все заявки или платные услуги
    public $request_all;

    // Парамаетры "Оперативной информации"
    public $report_status = [
        StatusRequest::STATUS_NEW => 'Новые',
        StatusRequest::STATUS_PERFORM => 'На исполнении',
        StatusRequest::STATUS_FEEDBACK => 'На уточнении',
        StatusRequest::STATUS_REJECT => 'Отклонены',
    ];

    public function init() {
        
        $current_date = time();
        $prev_month = strtotime('-1 month', time());
        
        if ($this->type == null && $this->request_id == null) {
            throw new NotFoundHttpException('Ошибка передачи параметров');
        }
        switch ($this->type) {
            case 'requests':
                $this->request_all = Requests::find()->where(['between', 'created_at', $prev_month, $current_date]);
                break;
            case 'paid-requests':
                $this->request_all = PaidServices::find()->where(['between', 'created_at', $prev_month, $current_date]);
                break;
            default:
                throw new NotFoundHttpException('Ошибка передачи параметров');
        }
        parent::init();
    }
    
    public function run() {
        
        $results = [];
        $count = 0;
        
        foreach ($this->report_status as $key => $status) {
            $count = $this->request_all->where(['status' => $key])->count();
            $temp = [
                $key => $count,
            ];
            $results += $temp;
        }
        
        $results += [
            '8' => $this->type == 'requests' ? 
                $this->request_all
                    ->orWhere(['IS', 'requests_specialist_id', NULL])
                    ->count() :
                $this->request_all
                    ->orWhere(['IS', 'services_specialist_id', NULL])
                    ->count(),
        ];
        
        return $this->render('reportbar/default', [
            'type_status' => $this->report_status,
            'results' => $results,
        ]);
        
    }
    
}
