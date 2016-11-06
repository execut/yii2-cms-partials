<?php

use yii\db\Schema;
use yii\db\Migration;

class m140930_065454_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->driverName === 'pgsql') {
            $this->execute('CREATE TYPE page_partials_type AS ENUM (\'system\',\'user-defined\')');
        }

        // Create 'page_partials' table
        $this->createTable('{{%page_partials}}', [
            'id'                    => $this->primaryKey(),
            'type'                  => 'page_partials_type NOT NULL DEFAULT \'user-defined\'',
            'name'                  => $this->string()->notNull(),
            'created_at'            => $this->integer()->notNull()->unsigned(),
            'updated_at'            => $this->integer()->notNull()->unsigned(),
        ], $tableOptions);

        // Create 'page_partials_lang' table
        $this->createTable('{{%page_partials_lang}}', [
            'page_partial_id'       => $this->integer()->notNull(),
            'language'              => $this->string(10)->notNull(),
            'title'                  => $this->string()->notNull(),
            'content'               => $this->text()->notNull(),
            'created_at'            => $this->integer()->notNull()->unsigned(),
            'updated_at'            => $this->integer()->notNull()->unsigned()
        ], $tableOptions);

        $this->addPrimaryKey('page_partial_id_language', '{{%page_partials_lang}}', ['page_partial_id', 'language']);
        $this->createIndex('language_i', '{{%page_partials_lang}}', 'language');
        $this->addForeignKey('FK_PAGE_PARTIALS_LANG_PAGE_PARTIAL_ID', '{{%page_partials_lang}}', 'page_partial_id', '{{%page_partials}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('page_partials_lang');
        $this->dropTable('page_partials');
    }
}
