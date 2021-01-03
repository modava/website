<?php

use yii\db\Migration;

/**
 * Class m200808_073103_create_talbe_website_information
 */
class m200808_073103_create_talbe_website_information extends Migration
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

        $this->createTable('{{%website_info}}', [
            'id' => $this->primaryKey(),
            'site_name' => $this->string(255)->notNull(),
            'about' => $this->json()->null(),
            'phone' => $this->json()->null(),
            'landline' => $this->json()->null(),
            'fax' => $this->json()->null(),
            'email' => $this->json()->null(),
            'address' => $this->json()->null(),
            'logo' => $this->string(255)->null(),
            'language' => $this->string(25)->null(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->addForeignKey('fk-website_info-created_by-user_id', 'website_info', 'created_by', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-website_info-updated_by-user_id', 'website_info', 'updated_by', 'user', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('website_info', [
            'id' => 1,
            'site_name' => 'Site name',
            'about' => [],
            'phone' => [],
            'landline' => [],
            'fax' => [],
            'email' => [],
            'address' => [],
            'language' => null,
            'logo' => null,
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%website_info}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200808_073103_create_talbe_website_information cannot be reverted.\n";

        return false;
    }
    */
}
