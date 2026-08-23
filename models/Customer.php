<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $type
 * @property string|null $comment
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
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
    public function rules()
    {
        return [
            [['address', 'comment'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'email', 'phone', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'email' => 'Email',
            'phone' => 'Телефон',
            'address' => 'Адреса',
            'type' => 'Тип',
            'comment' => 'Коментар',
            'status' => 'Статус',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCustomersStaffs()
    {
        return $this->hasMany(CustomersStaff::className(), ['customer_id' => 'id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['customer_id' => 'id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['entity_id' => 'id'])->andWhere(['entity_type' => Attachment::CUSTOMER]);
    }
}
