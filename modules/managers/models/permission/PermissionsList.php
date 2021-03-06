<?php

    namespace app\modules\managers\models\permission;

/*
 * Класс содержит массив разрешений для роли "Администратор"
 * 
 * $permissions_list = [
 *      'раздел Администратора' => [
 *          'название раздела' => '',
 *          'правила' => [
 *              'опредение правила в RBAC' => 'название',
 *              'опредение правила в RBAC' => 'название',
 *          ],
 *      ],
 * ]
 */    
class PermissionsList {
    
    public function getPermissionList($for_admnin) {
        
        $permissions_list = [
            'clients' => [
                'name' => 'Собственники',
                'permission' => [
                    'ClientsView' => 'Просмотр',
                    'ClientsEdit' => 'Редактирование',
                ]
            ],
            'employees' => [
                'name' => 'Сотрудники',
                'permission' => [
                    'EmployeesView' => 'Просмотр',
                    'EmployeesEdit' => 'Редактирование',
                ]
            ],
            'requests' => [
                'name' => 'Заявки',
                'permission' => [
                    'RequestsView' => 'Просмотр',
                    'RequestsEdit' => 'Редактирование',
                ]
            ],
            'paid-requests' => [
                'name' => 'Платные услуги',
                'permission' => [
                    'PaidRequestsView' => 'Просмотр',
                    'PaidRequestsEdit' => 'Редактирование',
                ]
            ],
            'news' => [
                'name' => 'Новости',
                'permission' => [
                    'NewsView' => 'Просмотр',
                    'NewsEdit' => 'Редактирование',
                ]
            ],
            'voting' => [
                'name' => 'Голосование',
                'permission' => [
                    'VotingsView' => 'Просмотр',
                    'VotingsEdit' => 'Редактирование',
                ]
            ],
            'designer' => [
                'name' => 'Конструктор заявок',
                'permission' => [
                    'DesignerView' => 'Просмотр',
                    'DesignerEdit' => 'Редактирование',
                ]
            ],
            'estates' => [
                'name' => 'Жилищный фонд',
                'permission' => [
                    'EstatesView' => 'Просмотр',
                    'EstatesEdit' => 'Редактирование',
                ]
            ],
            'settings' => [
                'name' => 'Настройки',
                'permission' => [
                    'SettingsView' => 'Просмотр',
                    'SettingsEdit' => 'Редактирование',
                ]
            ],
        ];
        
        return $for_admnin ? $permissions_list : 
                $permissions_list = [
                    'value' => 'CreateNewsDispatcher', 
                    'name' => 'Создание новостей'];
        
    }
    
    /*
     * Получить роли администратора
     */
    public static function getUserPermission($user_id) {
        
        $permission_list = [];
        
        $query = (new \yii\db\Query)
                ->select(['item_name', 'user_id'])
                ->from('auth_assignment')
                ->where(['user_id' => $user_id])
                ->all();

        foreach ($query as $key => $value) {
            $permission_list[$value['item_name']] = $key;
        }
        
        return $permission_list;
        
    }
    
    /*
     * 
     */
    public function canAddNews($user_id) {
        
        $query = (new \yii\db\Query)
                ->from('auth_assignment')
                ->where(['user_id' => $user_id])
                ->andWhere(['item_name' => 'CreateNewsDispatcher'])
                ->all();
        
        return $query ? true : false;
    }
}
