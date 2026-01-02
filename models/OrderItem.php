<?php

namespace app\models;

use Yii;

class OrderItem extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['quantity'], 'integer', 'min' => 1],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'order_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'order_item_id' => 'ID элемента заказа',
            'order_id' => 'ID заказа',
            'product_id' => 'ID товара',
            'quantity' => 'Количество',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['order_id' => 'order_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }
}
