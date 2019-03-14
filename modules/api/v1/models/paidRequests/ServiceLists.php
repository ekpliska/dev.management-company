<?php

    namespace app\modules\api\v1\models\paidRequests;
    use Yii;
    use app\models\CategoryServices;
    use app\models\Services;

/*
 * Категории платных услуг
 */    
class ServiceLists extends CategoryServices {
    
    /*
     * Список всех категорий с услугами
     */
    public static function gellFullServiceList() {
        
        $list = self::find()
                ->with('service')
                ->orderBy(['category_name' => SORT_ASC])
                ->all();
        
        $full_lists = [];
        
        foreach ($list as $key => $category) {
            $services = [];
            foreach ($category->service as $service) {
                $services[] = [
                    'service_id' => $service->service_id,
                    'name' => $service->service_name,
                    'image' => $service->service_image,
                ];
            }
            $full_lists['category_lists'][$key] = [
                'category_id' => $category->category_id,
                'category' => $category->category_name,
                'services' => $services,
            ];
        }
        
        return $full_lists;
        
    }
    
    /*
     * Список всех услуг по заданной категории
     */
    public static function getServicesByCatgory($category_id) {
        
        $list = Services::find()
                ->orderBy(['service_name' => SORT_ASC])
                ->where(['service_category_id' => $category_id])
                ->all();
        
        
        $full_lists = [];
        
        foreach ($list as $key => $category) {
            $full_lists[] = [
                'category_id' => $category->service_category_id,
                'service_id' => $category->service_id,
                'service_name' => $category->service_name,
                'description' => $category->service_description,
                'image' => $category->service_image,
            ];
        }
        
        return $full_lists;
        
    }
    
    /*
     * Просмотр отдельной услуги
     */
    public function getServiceInfo($service_id) {
        
        $info = Services::find()
                ->where(['service_id' => $service_id])
                ->one();
        
        return $info;
        
    }
    
    
}
