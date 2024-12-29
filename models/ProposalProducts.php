<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proposal_products".
 *
 * @property int $id
 * @property int|null $proposal_id
 * @property int|null $product_id
 * @property int|null $quantity
 * @property float|null $price
 */
class ProposalProducts extends \yii\db\ActiveRecord
{
    public $new_product;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proposal_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['proposal_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'proposal_id' => Yii::t('app', 'Пропозиція'),
            'product_id' => Yii::t('app', 'Продукт'),
            'quantity' => Yii::t('app', 'Кількість'),
            'price' => Yii::t('app', 'Ціна'),
            'new_product' => Yii::t('app', 'Новий продукт'),
        ];
    }
}
