<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_category}}`.
 */
class m230727_112012_create_payment_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(150),
            'description' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_category}}');
    }
}
