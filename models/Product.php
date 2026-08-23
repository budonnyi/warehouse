<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $articul
 * @property string|null $name
 * @property float|null $price
 * @property string|null $name_invoice
 * @property string|null $category_id
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['articul'], 'string', 'max' => 15],
            [['name', 'name_invoice', 'category_id'], 'string', 'max' => 255],
            [['name'], 'unique'],
//            [['name'], 'required'],
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
            'id'            => 'Індекс',
            'articul'       => 'Артикул',
            'name'          => 'Назва',
            'name_invoice'  => 'Назва в інвойсі',
            'category_id'   => 'Категорія',
            'status'        => 'Статус',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['entity_id' => 'id'])->andWhere(['entity_type' => Attachment::PRODUCT]);
    }
}
