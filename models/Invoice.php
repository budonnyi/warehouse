<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property string|null $order_num
 * @property string|null $order_date
 * @property string|null $invoice
 * @property string|null $date
 * @property string|null $date_customs
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

    public $customsexpances;

    public $total_sek_amount;
    public $total_sek_goods_amount;

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
            [['order_num', 'order_date', 'date', 'date_customs', 'bill_date', 'contract_date', 'newCustomer', 'customsexpances', 'total_sek_amount', 'through', 'total_sek_amount', 'total_sek_goods_amount'], 'safe'],
            [['customer_id', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'required'],
            [['total_amount', 'total_amount_sek', 'sek_rate', 'custom_taxes', 'transport_fee', 'brocker_fee', 'additional_cost', 'bank_fee'], 'number'],
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
            'order_num'         => Yii::t('app', 'Замовленя'),
            'order_date'        => Yii::t('app', 'Дата замовлення'),
            'invoice'           => Yii::t('app', 'Накладна'),
            'date'              => Yii::t('app', 'Дата'),
            'date_customs'      => Yii::t('app', 'Дата оформленя'),
            'bill'              => Yii::t('app', 'Рахунок'),
            'bill_date'         => Yii::t('app', 'Дата рахунку'),
            'contract'          => Yii::t('app', 'Контракт'),
            'contract_date'     => Yii::t('app', 'Дата контракту'),
            'customer_id'       => Yii::t('app', 'Покупець'),
            'through'           => Yii::t('app', 'Через'),
            'newCustomer'       => Yii::t('app', 'Новий покупець'),
            'quantity'          => Yii::t('app', 'Кількість'),
            'total_amount'      => Yii::t('app', 'Вартість грн'),
            'total_amount_sek'  => Yii::t('app', 'Вартість Sek'),
            'document_type'     => Yii::t('app', 'Тип'),
            'store'             => Yii::t('app', 'Склад'),
            'comment'           => Yii::t('app', 'Коментар'),
            'sek_rate'          => Yii::t('app', 'Курс Sek'),
            'custom_taxes'      => Yii::t('app', 'Митні платежі'),
            'transport_fee'     => Yii::t('app', 'Транспортні витрати'),
            'bank_fee'          => Yii::t('app', 'Комісія банку'),
            'brocker_fee'       => Yii::t('app', 'Брокар'),
            'additional_cost'   => Yii::t('app', 'Додаткова вартість'),
            'status'            => Yii::t('app', 'Статус'),
            'created_at'        => Yii::t('app', 'Created At'),
            'updated_at'        => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert) {
        $this->date = !empty($this->date) ? date('Y-m-d', strtotime($this->date)) : null;
        $this->date_customs = !empty($this->date_customs) ? date('Y-m-d', strtotime($this->date_customs)) : null;
        $this->bill_date = !empty($this->bill_date) ? date('Y-m-d', strtotime($this->bill_date)) : null;
        $this->contract_date = !empty($this->contract_date) ? date('Y-m-d', strtotime($this->contract_date)) : null;
        return parent::beforeSave($insert);
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

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['invoice_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['entity_id' => 'id'])->andWhere(['entity_type' => Attachment::INVOICE]);
    }
}
