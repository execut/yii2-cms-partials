<?php

use yii\db\Schema;
use yii\db\Migration;

class m140930_065454_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        // Create 'page_partials' table
        $this->createTable('{{%page_partials}}', [
            'id'                    => Schema::TYPE_PK . ' UNSIGNED',
            'type'                  => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        // Create 'page_partials_lang' table
        $this->createTable('{{%page_partials_lang}}', [
            'page_partial_id'       => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'content'               => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            0                       => 'PRIMARY KEY (`page_partial_id`, `language`)',
            1                       => 'INDEX `language` (`language`)'
        ], $tableOptions);
        
        $this->addForeignKey('FK_PAGE_PARTIALS_LANG_PAGE_PARTIAL_ID', '{{%page_partials_lang}}', 'page_partial_id', '{{%page_partials}}', 'id', 'RISTRICT', 'CASCADE');
    }

    public function down()
    {
        echo "m140930_065454_init cannot be reverted.\n";

        return false;
    }
}
