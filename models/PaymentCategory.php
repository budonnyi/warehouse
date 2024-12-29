<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_category".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $status
 */
class PaymentCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['category_id' => 'id']);
    }
}
