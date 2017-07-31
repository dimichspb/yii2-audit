<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170731_000004_alter_application_table extends Migration
{
    const TABLE = '{{%audit_application}}';

    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'author_id', $this->integer());
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE, 'author_id', $this->integer());
    }
}
