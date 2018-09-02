<?php

    use yii\db\Migration;

/**
 * Организация
 */
class m180901_105035_table_organizations extends Migration
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
        
        $this->createTable('{{%organizations}}', [
            'organizations_id' => $this->primaryKey(),
            'organizations_name' => $this->string(70)->notNull(),
        ], $table_options);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizations}}');
    }

}
