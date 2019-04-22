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
            'sms_code' => $this->string(100)->notNull(),
            'sms_text' => $this->text(250)->notNull(),
        ], $table_options);
        
        $this->batchInsert('{{%sms_settings}}', 
                ['sms_code', 'sms_text'], 
                [
                    ['register', 'Спасибо за регистрацию на нашем портале! Ваша учетная запись будет активирована после подтверждения СМС-кода. Ваш СМС-код: '],
                    ['repeat sms', 'Запрос повторной отправки СМС-кода. Ваш СМС-код: '],
                    ['participant at voting', 'Регистрация на участие в голосовании. Для продолжения укажите СМС-код: '],
                    ['recovery password', 'Подтверждение сброса забытого пароля. Для продолжения, укажите СМС-код: '],
                    ['change password', 'Подтверждение изменения пароля для доступа в личный кабинет. СМС-код подтверждения: '],
                    ['change mobile phone', 'Подтверждение изменения номера мобильного телефона. СМС-код подтверждения: '],
                    ['reset pin-code', 'Восстановление PIN-кода. Код-подтверждения: '],
                ]);
        
    }

    public function safeDown() {
        
        $this->dropTable('{{%sms_settings}}');
        
    }

}
