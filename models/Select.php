<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Select extends ActiveRecord
{
    public $customer;
    public $store;
    public $product;
    public $date;
    public $transfer_type;

    public function rules()
    {
        return [
            [['store', 'customer', 'product', 'date', 'transfer_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customer' => 'Клиент',
            'store' => 'Склад',
            'product' => 'Товар',
            'date' => 'Период',
            'transfer_type' => 'Transfer',
        ];
    }
}
