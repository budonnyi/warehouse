<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachment}}`.
 */
class m230913_053316_create_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attachment}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer(10),
            'filename' => $this->date(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%attachment}}');
    }
}
