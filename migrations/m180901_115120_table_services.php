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
        $this->createIndex('idx-category_services-category_name', '{{%category_services}}', 'category_name');
        
        // Единицы измерения
        $this->createTable('{{%units}}', [
            'units_id' => $this->primaryKey(),
            'units_name' => $this->integer()->notNull(),
        ]);        
        
        // Услуга
        $this->createTable('{{%services}}', [
            'service_id' => $this->primaryKey(),
            'services_category_id' => $this->integer()->notNull(),
            'services_name' => $this->string(255)->notNull(),
            'services_units_id' => $this->integer()->notNull(),
            'services_price' => $this->decimal(10,2)->notNull(),
            'services_description' => $this->text(255),
            'services_image' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-services-service_id', '{{%services}}', 'service_id');
        $this->createIndex('idx-services-services_name', '{{%services}}', 'services_name');
        
        
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
        $this->createIndex('idx-category_services-category_id', '{{%category_services}}');
        $this->createIndex('idx-category_services-category_name', '{{%category_services}}');
        
        $this->dropTable('{{%category_services}}');
        $this->dropTable('{{%units}}');

        $this->createIndex('idx-services-service_id', '{{%services}}');
        $this->createIndex('idx-services-services_name', '{{%services}}');
        
        $this->addForeignKey('fk-services-services_category_id', '{{%services}}');
        $this->addForeignKey('fk-services-services_units_id', '{{%services}}');
        
        $this->dropTable('{{%services}}');

    }

}
