<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170731_000003_alter_project_table extends Migration
{
    const TABLE = '{{%audit_project}}';

    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'author_id', $this->integer());
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE, 'author_id', $this->integer());
    }
}
