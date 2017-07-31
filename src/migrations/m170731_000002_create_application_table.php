<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170731_000002_create_application_table extends Migration
{
    const TABLE = '{{%audit_application}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'unique_id' => $this->string(64)->unique()->notNull(),
            'author_id' => $this->integer(),
            'project_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'token' => $this->string(64)->unique()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey('fk_application_author_id_user_user_id', self::TABLE, 'author_id', 'user', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_application_project_id_project_id', self::TABLE, 'project_id', 'audit_project', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
