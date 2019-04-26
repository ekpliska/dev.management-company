<?php

    use yii\db\Migration;
    use yii\db\Expression;

/**
 * Комментарий к приборам учета
 */
class m181219_063505_table_comments_to_counters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%comments_to_counters}}', [
            'id' => $this->primaryKey(),
            'comments_title' => $this->string(255)->notNull(),
            'comments_text' => $this->text(1000)->notNull(),
            'account_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->createIndex('idx-comments_to_counters-id', '{{%comments_to_counters}}', 'id');
        $this->createIndex('idx-comments_to_counters-account_id', '{{%comments_to_counters}}', 'account_id');
        
       $this->addForeignKey(
                'fk-comments_to_counters-account_id', 
                '{{%comments_to_counters}}', 
                'account_id', 
                '{{%personal_account}}', 
                'account_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
        $this->dropIndex('idx-comments_to_counters-id', '{{%comments_to_counters}}');
        $this->dropIndex('idx-comments_to_counters-account_id', '{{%comments_to_counters}}');
        
        $this->dropForeignKey('fk-comments_to_counters-account_id', '{{%comments_to_counters}}');
       
        $this->dropTable('{{%comments_to_counters}}');
        
    }

}
