<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%proposal_products}}`.
 */
class m230629_195509_create_proposal_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%proposal_products}}', [
            'id' => $this->primaryKey(),
            'proposal_id' => $this->integer(10),
            'product_id' => $this->integer(10),
            'quantity' => $this->integer(10),
            'price' => $this->decimal(10,2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%proposal_products}}');
    }
}
