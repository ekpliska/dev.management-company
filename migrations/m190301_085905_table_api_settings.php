<?php

    use yii\db\Migration;

/**
 * Настройки API Заказчика
 */
class m190301_085905_table_api_settings extends Migration {
    
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%api_settings}}', [
            'id' => $this->primaryKey(),
            'api_url' => $this->string(100)->notNull(),
        ], $table_options);
        
        $this->batchInsert('{{%api_settings}}', 
                ['api_url', 'protocol'], [
                    ['https://api.myelsa.ru/api/'],
                ]);
        
    }

    public function safeDown() {
        
        $this->dropTable('{{%api_settings}}');
        
    }

}
