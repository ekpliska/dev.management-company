<?php

use yii\db\Migration;

/**
 * Усуги
 */
class m180901_115120_table_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Категория услуги
        $this->createTable('{{%category_services}}', [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-category_services-category_id', '{{%category_services}}', 'category_id');
        
        // Единицы измерения
        $this->createTable('{{%units}}', [
            'units_id' => $this->primaryKey(),
            'units_name' => $this->string(100)->notNull(),
        ], $table_options);
        $this->batchInsert('{{%units}}', 
                ['units_id', 'units_name'], [
                    ['1', 'Кв. м.'],
                    ['2', 'Куб. м.'],
                    ['3', 'Вт']
                ]
        );
        
        // Услуга
        $this->createTable('{{%services}}', [
            'service_id' => $this->primaryKey(),
            'service_category_id' => $this->integer()->notNull(),
            'service_name' => $this->string(100)->notNull(),
            'service_unit_id' => $this->integer()->notNull(),
            'service_price' => $this->decimal(10,2)->notNull(),
            'service_description' => $this->text(1000)->notNull(),
            'service_image' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-services-service_id', '{{%services}}', 'service_id');
        
        $this->addForeignKey(
                'fk-services-services_category_id', 
                '{{%services}}', 
                'services_category_id', 
                '{{%category_services}}', 
                'category_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-services-services_units_id', 
                '{{%services}}', 
                'services_units_id', 
                '{{%units}}', 
                'units_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-category_services-category_id', '{{%category_services}}');
        $this->dropIndex('idx-category_services-category_name', '{{%category_services}}');
        
        $this->dropTable('{{%category_services}}');
        $this->dropTable('{{%units}}');

        $this->dropIndex('idx-services-service_id', '{{%services}}');
        $this->dropIndex('idx-services-services_name', '{{%services}}');
        
        $this->dropForeignKey('fk-services-services_category_id', '{{%services}}');
        $this->dropForeignKey('fk-services-services_units_id', '{{%services}}');
        
        $this->dropTable('{{%services}}');

    }

}
