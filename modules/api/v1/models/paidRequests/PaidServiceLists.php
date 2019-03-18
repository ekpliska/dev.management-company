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
                'services_id' => $request->services_id,
//                'servise_category' => $request->categoryService->category_name,
                'name_service' => $request->service->service_name,
                'comment' => $request->services_comment,
                'image' => $request->service->service_image,
            ];
        }

        return $results;
        
    }
    
}
