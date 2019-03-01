<?php

    use yii\db\Migration;

/**
 * FAQ
 */
class m190301_095319_table_faq_settings extends Migration {

    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%faq_settings}}', [
            'id' => $this->primaryKey(),
            'faq_question' => $this->string(100)->notNull(),
            'faq_answer' => $this->text(500)->notNull(),
        ], $table_options);
        
    }

    public function safeDown() {
        
        $this->dropTable('{{%faq_settings}}');
        
    }
    
}
