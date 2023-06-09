<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string|null $invoice
 * @property string|null $product_id
 * @property int|null $customer_id
 * @property int|null $quantity
 * @property float|null $price
 * @property string|null $transfer_type
 * @property string|null $date
 * @property string|null $store
 */
class Invoice extends \yii\db\ActiveRecord
{
    public $newCustomer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['date', 'newCustomer'], 'safe'],
            [['invoice', 'product_id'], 'string', 'max' => 15],
            [['transfer_type', 'store'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'Індекс',
            'invoice'       => 'Накладна',
            'product_id'    => 'Product ID',
            'newCustomer'   => 'Новий контрагент',
            'customer_id'   => 'Customer ID',
            'quantity'      => 'Кількість',
            'price'         => 'Ціна',
            'transfer_type' => 'Transfer Type',
            'date' => 'Дата',
            'store' => 'Склад',
        ];
    }

    public function getProducts()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getCustomers()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
}
