<?php

use yii\db\Migration;

/**
 * Class m201207_075138_create_table_phone_book
 */
class m201207_075138_create_table_phone_book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%website_phonebook}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'phone' => $this->string(20)->notNull(),
            'status' => $this->smallInteger(1),
            'description' => $this->text()->null(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->addForeignKey('fk-website_phonebook-user_created-by_user-id', 'website_phonebook', 'created_by', 'user', 'id');
        $this->addForeignKey('fk-website_phonebook-user_updated-by_user-id', 'website_phonebook', 'updated_by', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201207_075138_create_table_phone_book cannot be reverted.\n";

        return false;
    }
}
