<?php

use bedezign\yii2\audit\components\Migration;
use bedezign\yii2\audit\models\AuditApplication;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditProject;
use yii\db\Schema;

class m170731_000005_alter_audit_entry extends Migration
{
    const TABLE = '{{%audit_entry}}';

    public function safeUp()
    {
        $this->renameColumn(self::TABLE, 'application_id', 'application_unique_id');
        $this->addColumn(self::TABLE, 'application_id', $this->integer());

        /** @var AuditEntry[] $entries */
        $entries = AuditEntry::find()->all();

        if (!$defaultProject = AuditProject::find()->one()) {
            $defaultProject = new AuditProject([
                'name' => 'Default Project',
            ]);
            $defaultProject->save();
        }

        foreach ($entries as $entry) {
            if (is_null($entry->application_unique_id)) {
                $entry->application_unique_id = Yii::$app->id;
            }
            if (!$application = AuditApplication::findOne(['unique_id' => $entry->application_unique_id])) {
                /** @var AuditApplication $application */
                $application = new AuditApplication([
                    'unique_id' => $entry->application_unique_id,
                    'project_id' => $defaultProject->id,
                    'name' => $entry->application_unique_id,
                ]);
                $application->save(false);
            }
            $entry->application_id = $application->id;
            $entry->save(false);
        }
        $this->alterColumn(self::TABLE, 'application_id', $this->integer());

        $this->dropColumn(self::TABLE, 'application_unique_id');

        $this->addForeignKey('fk_entry_application_id_application_id', self::TABLE, 'application_id', 'audit_application', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_entry_application_id_application_id', self::TABLE);
        $this->addColumn(self::TABLE, 'application_unique_id', $this->string());

        /** @var AuditEntry[] $entries */
        $entries = AuditEntry::find()->all();

        foreach ($entries as $entry) {
            if ($application = AuditApplication::findOne(['id' => $entry->application_id])) {
                /** @var AuditApplication $application */
                $entry->application_unique_id = $application->unique_id;
                $entry->save(false);
            }
        }

        $this->dropColumn(self::TABLE, 'application_id');

        $this->renameColumn(self::TABLE, 'application_unique_id', 'application_id');
    }
}
