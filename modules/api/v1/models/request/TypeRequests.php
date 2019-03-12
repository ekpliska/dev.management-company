<?php

    namespace app\modules\api\v1\models\request;
    use app\models\TypeRequests as BaseTypeRequests;

/**
 * Description of TypeRequests
 *
 * @author dreamer
 */
class TypeRequests extends BaseTypeRequests {
    
    public static function getTypeID($type_name) {
        
        $type_info = self::find()
                ->where(['=', 'type_requests_name', $this->type_request])
                ->one();
                
        return $type_info ? true : false;
                
    }
    
}
