<?php

    namespace app\modules\api\v1\models;
    use app\models\Requests as BaseRequests;
    use app\models\StatusRequest;

/**
 * Заявки собсвеника
 */
class Requests extends BaseRequests {
    
    public static function getAllRequests($account_number) {
        
        $results = [];
        
        foreach (StatusRequest::getUserRequests() as $key => $status) {
            
            $requests = self::find()
                ->select(['requests_id', 'type_requests_name', 'status', 'requests_grade', 'created_at'])
                ->joinWith([
                    'typeRequest',
                    'personalAccount' => function ($query) use ($account_number) {
                        $query->andWhere(['account_number' => $account_number]);
                    }], false)
                ->where(['status' => $key])
                ->orderBy(['updated_at' => SORT_DESC])
                ->asArray()
                ->all();
                   
            $results[$status] = $requests;
        }            
                    
        return $results;
        
    }
    
    /*
     * Поиск заявки по его уникальному номеру
     */
    public static function findRequestByID($request_id) {

        $request = self::find()
                ->with([
                    'typeRequest', 
                    'employeeDispatcher', 
                    'image', 
                    'personalAccount', 'personalAccount.flat', 'personalAccount.flat.house'])
                ->where(['requests_id' => $request_id])
                ->one();
        
        if (empty($request)) {
            return [
                'success' => false,
                'message' => 'Заявка не найдена',
            ];
        }
        
        // Формируем результат
        $result = [];
        // Массив изображений
        $images = [];
        foreach ($request['image'] as $key => $value) {
            array_push($images, "/upload/store/{$value['filePath']}");
        }
        
        $result = [
            'request' => [
                'requests_ident' => $request->requests_ident,
                'type_requests_name' => $request->typeRequest->type_requests_name,
                'requests_comment' => $request->requests_comment,
                'requests_phone' => $request->requests_phone,
                'adress' => $request->personalAccount->flat->fullAdress,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                'date_closed' => $request->date_closed,
                'specialist' => !empty($request['employeeSpecialist']['fullName']) ? $request['employeeSpecialist']['fullName'] : 'Не незначен',
            ],
            'images' => !empty($images) ? $images : null,
        ];
       
        return $result;
        
    }
    
}
