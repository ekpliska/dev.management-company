<?php

    use yii\db\Migration;
    use app\models\SliderSettings;

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
            'is_show' => $this->integer()->defaultValue(SliderSettings::STATUS_SHOW),
        ], $table_options);
        
        $this->createIndex('idx-slider_settings-slider_id', '{{%slider_settings}}', 'slider_id');
        
        $this->batchInsert('{{%slider_settings}}', 
                ['slider_title', 'slider_text', 'button_1', 'button_2', 'is_show'], [
                    ['ELSA', 'Установите наше приложение и будьте всегда в курсе последних событий, оплачивайте услуги и участвуйте в голосованиях в любом месте', '', '', 1],
                ]);
        
    }

    public function safeDown() {
        
        $this->dropIndex('idx-slider_settings-slider_id', '{{%slider_settings}}');
        $this->dropTable('{{%slider_settings}}');
        
    }

}
