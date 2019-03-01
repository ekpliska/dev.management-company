<?php

    use yii\db\Migration;

/**
 * Слайдер
 */
class m190301_063724_table_slider_settings extends Migration {
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%slider_settings}}', [
            'slider_id' => $this->primaryKey(),
            'slider_title' => $this->string(100),
            'slider_text' => $this->string(255),
            'button_1' => $this->string(255),
            'button_2' => $this->string(255),
        ], $table_options);
        
        $this->createIndex('idx-token-slider_id', '{{%slider_settings}}', 'slider_id');
        
    }

    public function safeDown() {
        
        $this->dropIndex('idx-token-slider_id', '{{%slider_settings}}');
        $this->dropTable('{{%slider_settings}}');
        
    }

}
