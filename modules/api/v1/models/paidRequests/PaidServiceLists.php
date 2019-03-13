<?php

    namespace app\modules\api\v1\models\paidRequests;
    use Yii;
    use app\models\PaidServices;

/**
 * Список всех услуг, на платные заявки
 */
class PaidServiceLists extends PaidServices {
    
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
                'services_id' => $request->services_id,
                'servise_category' => $request->categoryService->category_name,
                'name_service' => $request->service->service_name,
                'description' => $request->service->service_description,
                'image' => $request->service->service_image,
            ];
        }

        return $results;
        
    }
    
}
