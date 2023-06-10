<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string|null $invoice
 * @property string|null $date
 * @property string|null $bill
 * @property string|null $bill_date
 * @property string|null $contract
 * @property string|null $contract_date
 * @property int|null $customer_id
 * @property int|null $quantity
 * @property float|null $total_amount
 * @property float|null $total_amount_sek
 * @property string|null $document_type
 * @property string|null $store
 * @property string|null $comment
 * @property float|null $sek_rate
 * @property float|null $custom_taxes
 * @property float|null $transport_fee
 * @property float|null $brocker_fee
 * @property float|null $additional_cost
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
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
            [['date', 'bill_date', 'contract_date'], 'safe'],
            [['customer_id', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_amount', 'total_amount_sek', 'sek_rate', 'custom_taxes', 'transport_fee', 'brocker_fee', 'additional_cost'], 'number'],
            [['invoice', 'document_type'], 'string', 'max' => 20],
            [['bill', 'contract'], 'string', 'max' => 30],
            [['store'], 'string', 'max' => 10],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at', 'updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => Yii::t('app', 'Індекс'),
            'invoice'           => Yii::t('app', 'Накладна'),
            'date'              => Yii::t('app', 'Дата'),
            'bill'              => Yii::t('app', 'Рахунок'),
            'bill_date'         => Yii::t('app', 'Дата рахунку'),
            'contract'          => Yii::t('app', 'Контракт'),
            'contract_date'     => Yii::t('app', 'Дата контракту'),
            'customer_id'       => Yii::t('app', 'Покупець'),
            'newCustomer'       => Yii::t('app', 'Новий покупець'),
            'quantity'          => Yii::t('app', 'Кількість'),
            'total_amount'      => Yii::t('app', 'Вартість грн'),
            'total_amount_sek'  => Yii::t('app', 'Вартість Sek'),
            'document_type'     => Yii::t('app', 'Тип'),
            'store'             => Yii::t('app', 'Склад'),
            'comment'           => Yii::t('app', 'Коментар'),
            'sek_rate'          => Yii::t('app', 'Курс Sek'),
            'custom_taxes'      => Yii::t('app', 'Митні платежі'),
            'transport_fee'     => Yii::t('app', 'Транспортe'),
            'brocker_fee'       => Yii::t('app', 'Брокар'),
            'additional_cost'   => Yii::t('app', 'AДодаткова варість'),
            'status'            => Yii::t('app', 'Статус'),
            'created_at'        => Yii::t('app', 'Created At'),
            'updated_at'        => Yii::t('app', 'Updated At'),
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

    public function getItems()
    {
        return $this->hasMany(InvoiceItem::className(), ['invoice_id' => 'id']);
    }
}
