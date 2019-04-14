<?php

    use yii\db\Migration;

/**
 * Настройки Сайта
 */
class m190301_085905_table_site_settings extends Migration {
    
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%site_settings}}', [
            'id' => $this->primaryKey(),
            'api_url' => $this->string(100)->notNull(),
            'welcome_text' => $this->text(1000),
            'user_agreement' => $this->text(2000)->notNull(),
            'promo_block' => $this->text(1000),
        ], $table_options);
        
        $this->batchInsert('{{%site_settings}}', 
                ['api_url', 'welcome_text', 'user_agreement'], [
                    [
                        'https://api.myelsa.ru/api/', 
                        'Добро пожаловать!',
                        'Пользовательское соглашение.',
                        'Промоблок',
                    ],
                ]);
        
    }

    public function safeDown() {
        
        $this->dropTable('{{%site_settings}}');
        
    }

}
