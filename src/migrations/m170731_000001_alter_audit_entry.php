<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170731_000001_alter_audit_entry extends Migration
{
    const TABLE = '{{%audit_entry}}';

    public function safeUp()
    {
        $this->renameColumn(self::TABLE, 'application', 'application_id');
    }

    public function safeDown()
    {
        $this->renameColumn(self::TABLE, 'application_id', 'application');
    }
}
