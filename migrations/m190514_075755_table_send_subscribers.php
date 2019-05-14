<?php

    use yii\db\Migration;
    use yii\db\Expression;

/**
 * Массовая рассылка уведомлений
 */
class m190514_075755_table_send_subscribers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%send_subscribers}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'type_post' => $this->string(10)->notNull(),
            'house_id' => $this->integer(),
            'status_subscriber' => $this->tinyInteger(),
            'date_create' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ], $table_options);
        
        $this->createIndex('idx-send_subscribers-id', '{{%send_subscribers}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
        $this->dropIndex($name, 'idx-send_subscribers-id', '{{%send_subscribers}}');
        $this->dropTable('{{%send_subscribers}}');
        
    }
}
