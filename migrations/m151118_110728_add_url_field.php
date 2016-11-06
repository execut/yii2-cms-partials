<?php

use yii\db\Schema;
use yii\db\Migration;

class m151118_110728_add_url_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%page_partials_lang}}', 'url', Schema::TYPE_STRING.'(255) NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%page_partials_lang}}', 'url');
    }
}
