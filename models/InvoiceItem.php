<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "invoice_item".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $product_id
 * @property int|null $quantity
 * @property float|null $price
 * @property float|null $price_sek
 * @property float|null $total_sek
 * @property float|null $surcharge_sek
 * @property string|null $comment
 */
class InvoiceItem extends \yii\db\ActiveRecord
{
    public $articul;
    public $new_product;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'product_id', 'quantity'], 'integer'],
//            [['quantity'], 'required'],
            [['price', 'price_sek', 'total_sek', 'surcharge_sek'], 'number'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'price_sek' => Yii::t('app', 'Price Sek'),
            'total_sek' => Yii::t('app', 'Total Sek'),
            'surcharge_sek' => Yii::t('app', 'Surcharge Sek'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }

    public function getProducts()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

}
