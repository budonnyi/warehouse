<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "proposal".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $title
 * @property string|null $person
 * @property string|null $content
 * @property string|null $customer_id
 * @property string|null $conditions
 * @property string|null $description
 * @property string|null $from
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Proposal extends \yii\db\ActiveRecord
{
    public $newCustomer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proposal';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['content', 'conditions', 'description', 'newCustomer'], 'string'],
            [['total_amount'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'person', 'customer_id', 'from'], 'string', 'max' => 255],
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
            'title' => Yii::t('app', 'Заголовок'),
            'person' => Yii::t('app', 'Кому'),
            'content' => Yii::t('app', 'Вміст'),
            'customer_id' => Yii::t('app', 'Контрагент'),
            'newCustomer' => Yii::t('app', 'Новий контрагент'),
            'conditions' => Yii::t('app', 'Додаткові умови'),
            'description' => Yii::t('app', 'Опис'),
            'from' => Yii::t('app', 'Склав/підписав'),
            'total_amount' => 'На суму',
            'status' => Yii::t('app', 'Статус'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
