<?php

    use yii\db\Migration;

/**
 * Настройка СМС оповещений
 */
class m190301_120646_table_sms_settings extends Migration {
    
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%sms_settings}}', [
            'id' => $this->primaryKey(),
            'sms_code' => $this->string(20)->notNull(),
            'sms_text' => $this->text(250)->notNull(),
        ], $table_options);
        
    }

    public function safeDown() {
        
        $this->dropTable('{{%sms_settings}}');
        
    }

}
