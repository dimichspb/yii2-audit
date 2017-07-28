<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170126_000001_alter_audit_mail extends Migration
{
    const TABLE = '{{%audit_entry}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'application', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'application');
    }
}
