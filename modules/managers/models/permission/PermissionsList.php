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
    
    public function getList() {
        
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
                    'PaidReuestsView' => 'Просмотр',
                    'PaidReuestsEdit' => 'Редактирование',
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
        
        return $permissions_list;
        
    }
    
    public static function getUserPermission($user_id) {
        
        $permission_list = [];
        
        $query = (new \yii\db\Query)
                ->select(['item_name'])
                ->from('auth_assignment')
                ->where(['user_id' => $user_id])
                ->all();
        
        foreach ($query as $key => $value) {
            $permission_list[$value['item_name']] = $key;
        }
        
//        echo '<pre>';
//        var_dump($permission_list);
//        die();
        return $permission_list;
        
    }
}
