<?php

use yii\db\Migration;

/**
 * Class m180901_211206_user_session
 */
class m180901_211206_user_session extends Migration
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
        
        $this->createTable('{{%user_session}}', [
            'id' => $this->primaryKey(),
            'expire' => $this->integer(),
            'data' => $this->longblob(),
        ], $table_options);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_session}}');
    }

}
