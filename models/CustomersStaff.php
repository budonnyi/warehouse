<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "customers_staff".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $comment
 * @property int|null $status
 * @property string|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $customer_id
 */
class CustomersStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_staff';
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
            [['comment'], 'string'],
            [['status', 'created_at', 'updated_at', 'customer_id'], 'integer'],
            [['name', 'email', 'phone'], 'string', 'max' => 100],
            [['position'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ПІБ',
            'email' => 'Email',
            'phone' => 'Телефон',
            'comment' => 'Коментар',
            'status' => 'Статус',
            'position' => 'Посада',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'customer_id' => 'Компанія',
        ];
    }
}
