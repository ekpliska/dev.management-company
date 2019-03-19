<?php

    namespace app\modules\api\v1\models\paidRequests;
    use Yii;
    use app\models\PaidServices;
    use app\models\CategoryServices;
    use app\models\PersonalAccount;

/**
 * Список всех услуг, на платные заявки
 */
class PaidServiceLists extends PaidServices {
    
    /*
     * Связь с таблицей Категории услуг
     */
    public function getCategoryService() {
        return $this->hasOne(CategoryServices::className(), ['category_id' => 'services_servise_category_id']);        
    }
    
    /*
     * Связь с таблицей Лицевой счет
     */
    public function getPersonalAccount() {
        return $this->hasOne(PersonalAccount::className(), ['account_id' => 'services_account_id']);        
    }
    
    public static function getAllPaidRequests($account_nubmer) {
        
        $requests = self::find()
                ->joinWith([
                    'service',
                    'categoryService',
                    'personalAccount' => function ($query) use ($account_nubmer) {
                        $query->andWhere(['account_number' => $account_nubmer]);
                    }
                    ], false)
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
                    
        
        $results = [];
        
        foreach ($requests as $key => $request) {
            $results['paid-requests'][] = [
                'paid_request_id' => $request->services_id,
                'servise_category' => $request->categoryService->category_name,
                'name_service' => $request->service->service_name,
                'comment' => $request->services_comment,
                'image' => $request->service->service_image,
            ];
        }

        return $results;
        
    }
    
    /*
     * Получить тело заявки
     */
    public static function getBodyRequest($request_id) {
        
        $body_request = self::find()
                ->with([
                    'service',
                    'categoryService',
                    'personalAccount', 'personalAccount.flat', 'personalAccount.flat.house'])
                ->where(['services_id' => $request_id])
                ->one();
        
        if (empty($body_request)) {
            return [
                'success' => false,
            ];
        }
        
        $result = [
            'paid_request_id' => $body_request->services_id,
            'paid_number' => $body_request->services_number,
            'category_name' => $body_request->categoryService->category_name,
            'service_name' => $body_request->service->service_name,
            'comment' => $body_request->services_comment,
            'date_created' => $body_request->created_at,
            'date_closed' => $body_request->date_closed,
            'status' => \app\models\StatusRequest::statusNameKey($body_request->status),
        ];
        
        return $result;
                    
    }
    
}
