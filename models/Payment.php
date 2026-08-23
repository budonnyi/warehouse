<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $description
 * @property string|null $direction
 * @property string|null $currency
 * @property float|null $amount
 * @property int|null $invoice_id
 * @property int|null $customer_id
 * @property int|null $category_id
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'description', 'currency', 'direction'], 'safe'],
            [['amount'], 'number'],
            [['invoice_id', 'customer_id', 'category_id', 'status', 'created_at', 'updated_at'], 'integer'],
//            [['created_at', 'updated_at'], 'required'],
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
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Дата'),
            'direction' => Yii::t('app', 'Напрямок'),
            'amount' => Yii::t('app', 'Сума'),
            'description' => Yii::t('app', 'Деталі платежу'),
            'currency' => Yii::t('app', 'Валюта'),
            'customer_id' => Yii::t('app', 'Контрагент'),
            'invoice_id' => Yii::t('app', 'Документ'),
            'category_id' => Yii::t('app', 'Категорія'),
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }

    public function getCategory()
    {
        return $this->hasOne(PaymentCategory::className(), ['id' => 'category_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['entity_id' => 'id'])->andWhere(['entity_type' => Attachment::PAYMENT]);
    }
}
